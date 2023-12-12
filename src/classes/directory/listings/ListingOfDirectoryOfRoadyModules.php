<?php

namespace Darling\RoadyModuleUtilities\classes\directory\listings;

use Darling\RoadyModuleUtilities\classes\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules as ListingOfDirectoryOfRoadyModulesInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \DirectoryIterator;

class ListingOfDirectoryOfRoadyModules implements ListingOfDirectoryOfRoadyModulesInterface
{

    public function __construct(
        private PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
    ) {}

    public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules
    {
        return $this->pathToDirectoryOfRoadyModules;
    }

    public function pathToRoadyModuleDirectoryCollection(): PathToRoadyModuleDirectoryCollection
    {
        $directoryIterator = new DirectoryIterator($this->pathToDirectoryOfRoadyModules()->__toString());
        $pathToRoadyModuleDirectoryInstances = [];
        foreach($directoryIterator as $fileInfo) {
            if($fileInfo->isDot()) {
                continue;
            }
            if(is_dir($fileInfo->getRealPath())) {
                $pathToRoadyModuleDirectoryInstances[] = new PathToRoadyModuleDirectory(
                    $this->pathToDirectoryOfRoadyModules(),
                    new Name(new Text($fileInfo->getFilename())),
                );
            }
        }
        return new PathToRoadyModuleDirectoryCollection(
            ...$pathToRoadyModuleDirectoryInstances
        );
    }

}

