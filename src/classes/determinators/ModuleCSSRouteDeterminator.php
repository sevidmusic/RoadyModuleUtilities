<?php

namespace Darling\RoadyModuleUtilities\classes\determinators;

use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator as ModuleCSSRouteDeterminatorInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

class ModuleCSSRouteDeterminator implements ModuleCSSRouteDeterminatorInterface
{

    public function determineCSSRoutes(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): RouteCollection
    {
        return new RouteCollectionInstance();
    }

}

