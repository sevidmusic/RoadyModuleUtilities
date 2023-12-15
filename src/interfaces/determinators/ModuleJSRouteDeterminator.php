<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

/**
 * A ModuleJSRouteDeterminator can be used to dynamically determine
 * the Routes to the js files that exist in a Roady module's js
 * directory, and it's sub-directories.
 *
 */
interface ModuleJSRouteDeterminator
{

    /**
     * Determine the Routes to the js files that exist in the
     * specified Roady module's js directory, and it's sub-directories.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The PathToRoadyModuleDirectory
     *                                   that defines the path to the
     *                                   module whose js Routes will
     *                                   be determined.
     *
     * @return RouteCollection
     *
     */
    public function determineJSRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

