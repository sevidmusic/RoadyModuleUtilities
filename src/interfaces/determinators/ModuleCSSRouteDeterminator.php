<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

/**
 * A ModuleCSSRouteDeterminator can be used to dynamically determine
 * the Routes to the css files that exist in a Roady module's css
 * directory, and it's sub-directories.
 *
 */
interface ModuleCSSRouteDeterminator
{

    /**
     * Determine the Routes to the css files that exist in the
     * specified Roady module's css directory, and it's sub-directories.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The PathToRoadyModuleDirectory
     *                                   that defines the path to the
     *                                   module whose css Routes will
     *                                   be determined.
     *
     * @return RouteCollection
     *
     */
    public function determineCSSRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

