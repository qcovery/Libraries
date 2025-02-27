<?php
namespace Libraries\Module\Configuration;

$config = [
    'service_manager' => [
        'allow_override' => true,
        'factories' => [
            'Libraries\AjaxHandler\GetLibraries' => 'Libraries\AjaxHandler\GetLibrariesFactory',
            'Libraries\Search\BackendManager' => 'Libraries\Search\BackendManagerFactory',
            'Libraries\Search\Search2\Params' => 'Libraries\Search\Solr\ParamsFactory',
            'Libraries\Search\Solr\Params' => 'Libraries\Search\Solr\ParamsFactory',
            'VuFind\Search\Search2\Results' => 'Libraries\Search\Search2\ResultsFactory',
            'VuFind\Search\Solr\Results' => 'Libraries\Search\Solr\ResultsFactory',
        ],
        'aliases' => [
            'getLibraries' => 'Libraries\AjaxHandler\GetLibraries',
            'search2' => 'Libraries\Search\Search2\Params',
            'solr' => 'Libraries\Search\Solr\Params',
            'VuFind\Search\BackendManager' => 'Libraries\Search\BackendManager',
        ],
    ],
    'vufind' => [
        'plugin_managers' => [
            'ajaxhandler' => [
                'factories' => [
                    'Libraries\AjaxHandler\GetLibraries' => 'Libraries\AjaxHandler\GetLibrariesFactory',
                ],
                'aliases' => [
                    'getLibraries' => 'Libraries\AjaxHandler\GetLibraries',
                ],
            ],
            'search_params' => [
                'factories' => [
                    'Libraries\Search\Search2\Params' => 'Libraries\Search\Solr\ParamsFactory',
                    'Libraries\Search\Solr\Params' => 'Libraries\Search\Solr\ParamsFactory',
                ],
                'aliases' => [
                    'search2' => 'Libraries\Search\Search2\Params',
                    'solr' => 'Libraries\Search\Solr\Params',
                    'VuFind\Search\Search2\Params' => 'Libraries\Search\Search2\Params',
                    'VuFind\Search\Solr\Params' => 'Libraries\Search\Solr\Params',
                ],
            ],
            'search_results' => [
                'factories' => [
                    'Libraries\Search\Solr\Results' => 'Libraries\Search\Results\ResultsFactory',
                ],
                'aliases' => [
                    'solr' => 'Libraries\Search\Solr\Results',
                ],
            ],
        ],
    ],
];

return $config;

