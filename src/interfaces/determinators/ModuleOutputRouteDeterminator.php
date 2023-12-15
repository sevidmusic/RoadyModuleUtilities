<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

/**
 * A ModuleOutputRouteDeterminator can be used to dynamically determine
 * the Routes to the output files that exist in a Roady module's output
 * directory, and it's sub-directories.
 *
 */
interface ModuleOutputRouteDeterminator
{

    /**
     * Determine the Routes to the output files that exist in the
     * specified Roady module's output directory, and it's sub-directories.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The PathToRoadyModuleDirectory
     *                                   that defines the path to the
     *                                   module whose output Routes will
     *                                   be determined.
     *
     * @return RouteCollection
     *
     */
    public function determineOutputRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

