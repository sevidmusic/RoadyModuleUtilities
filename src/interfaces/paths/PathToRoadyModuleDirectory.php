<?php

namespace Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Stringable;

/**
 * A PathToDirectoryOfRoadyModules can be used to determine the path
 * to a specific Roady module's root directory if it exists in the
 * directory located at the path defined by an instance of a
 * PathToDirectoryOfRoadyModules.
 *
 * If the module's root directory does not exist in the relevant
 * directory then the path will default to the path returned by
 * php's sys_get_temp_dir() function.
 *
 */
interface PathToRoadyModuleDirectory extends Stringable
{

    /**
     * Return the Name of the module.
     *
     * @return Name
     *
     */
    public function name(): Name;

    /**
     * Return an instance of a PathToDirectoryOfRoadyModules that will
     * determine the path to the directory where the module's root
     * directory is expected to be located.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules;

    /**
     * Return an instance of a PathToExistingDirectory that will
     * determine the complete path to the module's root directory
     * if the module exists.
     *
     * If the module does not exist then return an instance of a
     * PathToExistingDirectory that defines a path to the systems
     * temporary directory.
     *
     * @return PathToExistingDirectory
     *
     */
    public function pathToExistingDirectory(): PathToExistingDirectory;

    /**
     * Return the complete path to the Roady module's root directory,
     * or to the systems temporary directory if the module does not
     * exist at the path defined by the PathToDirectoryOfRoadyModules
     * instance returned by the pathToDirectoryOfRoadyModules() method.
     *
     * @return string
     *
     */
    public function __toString(): string;

}

