<?php
/**
 *
 */
namespace Libraries\View\Helper\Libraries;

use Libraries\Libraries;

class ConnectedLibraries extends \Laminas\View\Helper\AbstractHelper
{
    protected $Libraries;

    /**
     * Constructor
     */
    public function __construct($config, \VuFind\Search\Memory $memory)
    {
        $this->Libraries = new Libraries($config, $memory);
    }


    /**
     * Get all connected libraries as array.
     *
     * @param $searchClassId
     * @param $driver
     * @return array
     */
    public function getConnectedLibraries($searchClassId, $driver = null)
    {
        $libraryCodes = $this->Libraries->getLibraryCodes($searchClassId);
        if (!empty($driver)) {
            $collectionDetails = $driver->getMarcData('CollectionDetails');
            $holdingCodes = [];
            if (is_array($collectionDetails)) {
                foreach ($collectionDetails as $collectionDetail) {
                    if (isset($collectionDetail['code']['data'][0])) {
                        $holdingCodes[] = $collectionDetail['code']['data'][0];
                    }
                }
            }
            $libraryCodes = array_intersect($libraryCodes, $holdingCodes);
        }
        $connectedLibraries = [];
        foreach ($libraryCodes as $libraryCode) {
            $connectedLibraries[] = $this->Libraries->getLibrary($libraryCode);
        }
        return $connectedLibraries;
    }

    /**
     * Get the selected libary.
     *
     * @return array|null
     */
    public function getSelectedLibrary() {
        return $this->Libraries->selectLibrary();
    }

    /**
     * Get the codes of all connected libraries as array.
     *
     * @param $searchClassId
     * @return array
     */
    public function getConnectedLibrariesCodes($searchClassId) {
        return array_unique($this->Libraries->getLibraryCodes($searchClassId));
    }

    /**
     *
     */
    public function getConnectedLibrariesLinkData($libraryCode) {
        return $this->Libraries->getLibraryLinkData($libraryCode);
    }
}
