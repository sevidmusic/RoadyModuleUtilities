<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

/**
 * Description of this interface.
 *
 * @example
 *
 * ```
 *
 * ```
 */
interface ModuleCSSRouteDeterminator
{
     public function determineCSSRoutes(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): RouteCollection;
}

