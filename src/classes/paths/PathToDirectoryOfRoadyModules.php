<?php

namespace Darling\RoadyModuleUtilities\classes\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules as PathToDirectoryOfRoadyModulesInterface;

class PathToDirectoryOfRoadyModules implements PathToDirectoryOfRoadyModulesInterface
{

    /**
     * Instantiate a new PathToDirectoryOfRoadyModules instance.
     *
     * @param PathToExistingDirectory $pathToExistingDirectory
     *                        An instance of a PathToExistingDirectory
     *                        that will determine the complete path to
     *                        the directory of Roady modules.
     *
     *
     */
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

