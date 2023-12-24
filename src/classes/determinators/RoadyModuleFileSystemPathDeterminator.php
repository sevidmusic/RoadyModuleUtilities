<?php

namespace Darling\RoadyModuleUtilities\classes\determinators;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingFile as PathToExistingFileInstance;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator as RoadyModuleFileSystemPathDeterminatorInterface;
use \Darling\RoadyRoutes\interfaces\paths\RelativePath;

class RoadyModuleFileSystemPathDeterminator implements RoadyModuleFileSystemPathDeterminatorInterface
{

    public function determinePathToFileInModuleDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RelativePath $relativePath
    ): PathToExistingFile
    {
        $pathToFile = $pathToRoadyModuleDirectory->__toString() .
            DIRECTORY_SEPARATOR .
            $relativePath->__toString();
        $fileName = basename($pathToFile);
        $parts = explode(DIRECTORY_SEPARATOR, $pathToFile);
        $safeTextParts = [];
        foreach($parts as $part) {
            if(!empty($part) && $part !== $fileName) {
                $safeTextParts[] = new SafeText(new Text($part));
            }
        }
        $pathToFilesParentDirectory = new PathToExistingDirectory(
            new SafeTextCollection(...$safeTextParts),
        );
        return new PathToExistingFileInstance(
            $pathToFilesParentDirectory,
            new Name(new Text($fileName)),
        );
    }

}

