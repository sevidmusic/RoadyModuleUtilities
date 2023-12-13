<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\paths\RelativePath;

/**
 * A RoadyModuleFileSystemPathDeterminator can be used to determine
 * the path to an existing file in a specific Roady module's directory.
 */
interface RoadyModuleFileSystemPathDeterminator
{

    /**
     * If the specified $pathToRoadyModuleDirectory and $relativePath
     * can be combined to form a path to an exisitng file then return
     * an instance of a PathToExistingFile  that defines a path to
     * that file.
     *
     * If the specified $pathToRoadyModuleDirectory and $relativePath
     * can not be combined to form a path to an exisitng file then
     * return an instance of a PathToExistingFile that defines a path
     * to an empty temporary file in the systems temporary directory.
     *
     * @return PathToExistingFile
     *
     */
    public function determinePathToFileInModuleDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RelativePath $relativePath
    ): PathToExistingFile;

}

