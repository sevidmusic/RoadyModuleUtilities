<?php

namespace Darling\RoadyModuleUtilities\tests\classes\collections;

use Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\collections\PathToRoadyModuleDirectoryCollectionTestTrait;

class PathToRoadyModuleDirectoryCollectionTest extends RoadyModuleUtilitiesTest
{

    /**
     * The PathToRoadyModuleDirectoryCollectionTestTrait defines
     * common tests for implementations of the
     * \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection
     * interface.
     *
     * @see PathToRoadyModuleDirectoryCollectionTestTrait
     *
     */
    use PathToRoadyModuleDirectoryCollectionTestTrait;

    public function setUp(): void
    {
        $collection = [
            new PathToRoadyModuleDirectory(
                new PathToDirectoryOfRoadyModules(
                    new PathToExistingDirectory(
                        new SafeTextCollection(),
                    ),
                ),
                new Name(new Text($this->randomChars())),
            ),
        ];
        $this->setExpectedCollection(...$collection);
        $this->setPathToRoadyModuleDirectoryCollectionTestInstance(
            new PathToRoadyModuleDirectoryCollection(...$collection)
        );
    }
}

