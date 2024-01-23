<?php

namespace Darling\RoadyModuleUtilities\interfaces\configuration;

use \Darling\PHPWebPaths\interfaces\paths\parts\url\Authority;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
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
     * Read Routes configuration file and return a RouteCollection
     * that contains all of the Routes defined in the Routes
     * configuration file.
     *
     * The name of the file to read is determined by the specified
     * Authority.
     *
     * @param Authority $authority The Authority that corresponds to
     *                             the name of the configuration file
     *                             to read.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The PathToRoadyModuleDirectory
     *                                   that defines the path to the
     *                                   module.
     *
     * @param RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
     *                   A RoadyModuleFileSystemPathDeterminator
     *                   instance that will be used to determine the
     *                   complete path to the modules routes.json
     *                   configuration file.
     *
     * @return RouteCollection
     *
     */
    public function determineConfiguredRoutes(
        Authority $authority,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
    ): RouteCollection;

}

