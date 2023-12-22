<?php

namespace Darling\RoadyModuleUtilities\classes\configuration;

use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader as ModuleRoutesJsonConfigurationReaderInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;

class ModuleRoutesJsonConfigurationReader implements ModuleRoutesJsonConfigurationReaderInterface
{

    public function determineConfiguredRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        return new RouteCollectionInstance();
    }
}

