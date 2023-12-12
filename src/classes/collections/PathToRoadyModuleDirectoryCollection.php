<?php

namespace Darling\RoadyModuleUtilities\classes\collections;

use \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection as PathToRoadyModuleDirectoryCollectionInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

class PathToRoadyModuleDirectoryCollection implements PathToRoadyModuleDirectoryCollectionInterface
{

    /**
     * @var array<int, PathToRoadyModuleDirectory> $pathsToRoadyModuleDirectories
     *                      A collection of PathToRoadyModuleDirectory
     *                      instances.
     */
    private $pathsToRoadyModuleDirectories = [];

    public function __construct(PathToRoadyModuleDirectory ...$pathsToRoadyModuleDirectories)
    {
        foreach ($pathsToRoadyModuleDirectories as $pathsToRoadyModuleDirectory) {
            $this->pathsToRoadyModuleDirectories[] = $pathsToRoadyModuleDirectory;
        }
    }

    public function collection(): array
    {
        return $this->pathsToRoadyModuleDirectories;
    }
}

