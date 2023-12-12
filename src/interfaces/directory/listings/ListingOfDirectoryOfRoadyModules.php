<?php

namespace Darling\RoadyModuleUtilities\interfaces\directory\listings;

use Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;

/**
 * A ListingOfDirectoryOfRoadyModules defines a
 * PathToRoadyModuleDirectoryCollection that
 * defines a collection of PathToRoadyModuleDirectory
 * instances for the directories located in the assigned
 * PathToDirectoryOfRoadyModules.
 *
 */
interface ListingOfDirectoryOfRoadyModules
{

    /**
     * Return the PathToDirectoryOfRoadyModules that will be listed.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules;


    /**
     * Return the PathToRoadyModuleDirectoryCollection of
     * PathToRoadyModuleDirectory instances for the directories
     * located in the assigned PathToDirectoryOfRoadyModules.
     *
     * @return PathToRoadyModuleDirectoryCollection
     *
     */
    public function pathToRoadyModuleDirectoryCollection(): PathToRoadyModuleDirectoryCollection;

}

