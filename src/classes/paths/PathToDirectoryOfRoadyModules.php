<?php

namespace Darling\RoadyModuleUtilities\classes\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules as PathToDirectoryOfRoadyModulesInterface;

class PathToDirectoryOfRoadyModules implements PathToDirectoryOfRoadyModulesInterface
{

    public function __construct(
        private PathToExistingDirectory $pathToExistingDirectory
    ) {}

    public function pathToExistingDirectory(): PathToExistingDirectory
    {
        return $this->pathToExistingDirectory;
    }

    public function __toString(): string
    {
        return $this->pathToExistingDirectory()->__toString();
    }
}

