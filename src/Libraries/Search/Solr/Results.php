<?php

/**
 * Solr aspect of the Search Multi-class (Results)
 *
 * PHP version 7
 *
 * Copyright (C) Villanova University 2011, 2022.
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
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category VuFind
 * @package  Search_Solr
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */

namespace Libraries\Search\Solr;

use VuFind\Search\Solr\ArrayObject;

use Libraries\Search\Factory\UrlQueryHelperFactory;

/**
 * Solr Search Parameters
 *
 * @category VuFind
 * @package  Search_Solr
 * @author   Demian Katz <demian.katz@villanova.edu>
 * @author   David Maus <maus@hab.de>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://vufind.org Main Page
 */
class Results extends \VuFind\Search\Solr\Results
{
    /**
     * Get URL query helper factory
     *
     * @return UrlQueryHelperFactory
     */
    protected function getUrlQueryHelperFactory()
    {
        if (null === $this->urlQueryHelperFactory) {
            $this->urlQueryHelperFactory = new UrlQueryHelperFactory();
        }
        return $this->urlQueryHelperFactory;
    }

    /**
     * Get the URL helper for this object.
     *
     * @return \VuFind\Search\UrlQueryHelper
     */
    public function getUrlQuery()
    {
        // Set up URL helper:
        if (!isset($this->helpers['urlQuery'])) {
            $factory = $this->getUrlQueryHelperFactory();
            $this->helpers['urlQuery'] = $factory->fromParams(
                $this->getParams(),
                $this->getUrlQueryHelperOptions()
            );
        }
        return $this->helpers['urlQuery'];
    }
}
