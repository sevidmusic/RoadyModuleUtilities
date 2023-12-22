<?php

namespace Darling\RoadyModuleUtilities\interfaces\configuration;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

/**
 * A ModuleRoutesJsonConfigurationReader can be used to determine
 * the Routes defined in a Roady module's `routes.json` configuration
 * file.
 *
 */
interface ModuleRoutesJsonConfigurationReader
{
 /**
     * Read the module's `routes.json` configuration file and return
     * a RouteCollection that contains all of the Routes defined
     * in the `routes.json` configuration file.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The PathToRoadyModuleDirectory
     *                                   that defines the path to the
     *                                   module.
     *
     * @return RouteCollection
     *
     */
    public function determineConfiguredRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

