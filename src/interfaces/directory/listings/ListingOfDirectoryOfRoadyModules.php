<?php

namespace Darling\RoadyModuleUtilities\interfaces\directory\listings;

use Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;

/**
 * Description of this interface.
 *
 * @example
 *
 * ```
 *
 * ```
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

    public function pathToRoadyModuleDirectoryCollection(): PathToRoadyModuleDirectoryCollection;

}

