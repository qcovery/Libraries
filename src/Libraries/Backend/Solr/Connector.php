<?php
/**
 * Solr Connector with Libraries Extension
 *
 * PHP version 5
 *
 * Copyright (C) Staats- und Universitätsbibliothek 2017.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category VuFind2
 * @package  Backend
 * @author   Hajo Seng <hajo.seng@sub.uni-hamburg.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/subhh/beluga
 */
namespace Libraries\Backend\Solr;

use VuFindSearch\Backend\Solr\HandlerMap;
use VuFindSearch\ParamBag;

use Laminas\Http\Request;
use Laminas\Http\Client as HttpClient;
use Laminas\Http\Client\Adapter\AdapterInterface;

class Connector extends \VuFindSearch\Backend\Solr\Connector
{

    /**
     * HTTP read timeout.
     *
     * @var int
     */
    protected int $timeout = 30;

    /**
     * Proxy service
     *
     * @var mixed
     */
    protected $proxy;

    /**
     * HTTP client adapter.
     *
     * Either the class name or a adapter instance.
     *
     * @var string|AdapterInterface
     */
    protected $adapter = 'Laminas\Http\Client\Adapter\Socket';

    /**
     * Library Filter Type: either 'url' if url-Filters are supported
     *                          or 'fq' otherwise
     *
     * @var string
     */
    const LIBRARY_FILTER_TYPE = 'filter';

    /**
     * Send query to SOLR and return response body.
     *
     * @param string   $handler SOLR request handler to use
     * @param ParamBag $params  Request parameters
     *
     * @return string Response body
     */
    public function query($handler, ParamBag $params, bool $cacheable = false)
    {
        // TODO $cacheable should be implemented
        $url = $this->addLibraryFilter($handler, $params);
        $params->remove('mm');
        $facetFieldParam = $params->get('facet.field');
        if (!$facetFieldParam) {
            $params->remove('facet.field');
        } else if (is_array($facetFieldParam)) {
            foreach ($facetFieldParam as $index => $facetFieldEntry) {
                if ($facetFieldEntry == '') {
                    unset($facetFieldParam[$index]);
                }
            }
            if (empty($facetFieldParam)) {
                $params->remove('facet.field');
            }
        }        
        $paramString = implode('&', $params->request());

        if (strlen($paramString) > self::MAX_GET_URL_LENGTH) {
            $method = Request::METHOD_POST;
        } else {
            $method = Request::METHOD_GET;
        }

        if ($method === Request::METHOD_POST) {
            $client = $this->createClient($url, $method);
            $client->setRawBody($paramString);
            $client->setEncType(HttpClient::ENC_URLENCODED);
            $client->setHeaders(array('Content-Length' => strlen($paramString)));
        } else {
            $url = (strpos($url, '?') === false) ? $url . '?' . $paramString : $url . '&' . $paramString;
            $client = $this->createClient($url, $method);
        }
        if ($this->logger) {
            $this->logger->debug('Query' . urldecode($paramString));
        }

        return $this->send($client);
    }

    /**
     * Beluga Core Libraries
     * Set Library Filters
     *
     * param string   $handler SOLR request handler to use
     * @param ParamBag $params  Request parameters
     *
     * @return string
     */
    private function addLibraryFilter($handler, ParamBag $params)
    {
        $includedLibraries = $params->get('included_libraries');
        $excludedLibraries = $params->get('excluded_libraries');

        $params->remove('included_libraries');
        $params->remove('excluded_libraries');
        $space = (self::LIBRARY_FILTER_TYPE == 'url') ? '%20' : '+';
        if (!empty($includedLibraries)) {
            $libraryFilter = '('.implode($space.'OR'.$space, $includedLibraries).')';
            if (!empty($excludedLibraries)) {
                $libraryFilter = '('.$libraryFilter.$space.'NOT'.$space.'('.implode($space.'OR'.$space, $excludedLibraries).'))';
            }
        }
        if (self::LIBRARY_FILTER_TYPE == 'url') {
            return (empty($libraryFilter)) ? $this->url . '/filter/' . $handler : $this->url . '/filter/' . $libraryFilter . '/' . $handler;
        } else {
            return (empty($libraryFilter)) ? $this->url . '/' . $handler : $this->url . '/' . $handler . '?fq=' . $libraryFilter;
        }
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return mixed
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @param mixed $proxy
     */
    public function setProxy($proxy): void
    {
        $this->proxy = $proxy;
    }

    /**
     * Create the HTTP client.
     *
     * @param string $url    Target URL
     * @param string $method Request method
     *
     * @return \Laminas\Http\Client
     */
    protected function createClient($url, $method): HttpClient
    {
        $client = new HttpClient();
        $client->setAdapter($this->adapter);
        $client->setOptions(['timeout' => $this->timeout]);
        $client->setUri($url);
        $client->setMethod($method);
        if ($this->proxy) {
            $this->proxy->proxify($client);
        }
        return $client;
    }
}


