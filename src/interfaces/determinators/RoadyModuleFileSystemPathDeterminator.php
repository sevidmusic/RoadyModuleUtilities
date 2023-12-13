<?php

namespace Darling\RoadyModuleUtilities\interfaces\determinators;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\paths\RelativePath;

interface RoadyModuleFileSystemPathDeterminator
{

    public function determinePathToFileInModuleDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RelativePath $relativePath
    ): PathToExistingFile;

}

