<?php

namespace Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Stringable;

/**
 * A PathToDirectoryOfRoadyModules can be used to define a path to
 * a directory where Roady modules are expected to be located.
 *
 */
interface PathToDirectoryOfRoadyModules extends Stringable
{

    /**
     * Return an instance of a PathToExistingDirectory that will
     * determine the path to the directory of Roady modules.
     *
     * @return PathToExistingDirectory
     *
     */
    public function pathToExistingDirectory(): PathToExistingDirectory;

    /**
     * Return the path to the directory of Roady modules.
     *
     * The complete path is determined by the PathToExistingDirectory
     * instance returned by the pathToExistingDirectory() method.
     *
     * @return string
     *
     */
    public function __toString(): string;

}

