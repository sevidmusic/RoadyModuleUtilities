<?php

namespace Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Stringable;

/**
 * A PathToDirectoryOfRoadyModules defines a PathToExistingDirectory
 * where Roady modules are expected to be located.
 *
 * The path can be obtained via the __toString() method.
 *
 */
interface PathToDirectoryOfRoadyModules extends Stringable
{

    /**
     * Return the assigned PathToExistingDirectory which will
     * determine the actual path to the directory of Roady modules.
     *
     * @return PathToExistingDirectory
     *
     */
    public function pathToExistingDirectory(): PathToExistingDirectory;

    /**
     * Return the path to the directory of Roady modules.
     *
     * @return string
     *
     */
    public function __toString(): string;

}

