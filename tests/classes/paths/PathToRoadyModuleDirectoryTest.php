<?php

namespace Darling\RoadyModuleUtilities\tests\classes\paths;

use Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\paths\PathToRoadyModuleDirectoryTestTrait;

class PathToRoadyModuleDirectoryTest extends RoadyModuleUtilitiesTest
{

    /**
     * The PathToRoadyModuleDirectoryTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory
     * interface.
     *
     * @see PathToRoadyModuleDirectoryTestTrait
     *
     */
    use PathToRoadyModuleDirectoryTestTrait;

    public function setUp(): void
    {
        $pathToExistingDirectory = new PathToExistingDirectory(
            $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
        );
        $pathToNonExistingDirectory = new PathToExistingDirectory(
            $this->safeTextCollectionThatMapsToADirectoryThatDoesNotExist()
        );
        $testDirectory = (rand(0, 1) ? $pathToExistingDirectory : $pathToNonExistingDirectory);
        $pathToDirectoryOfRoadyModules = new PathToDirectoryOfRoadyModules(
                $testDirectory
        );
        $this->setExpectedPathToDirectoryOfRoadyModules($pathToDirectoryOfRoadyModules);
        $name = new Name(new Text(rand(0, 1) ? 'interfaces' : $this->randomChars()));
        $this->setExpectedName($name);
        $this->setPathToRoadyModuleDirectoryTestInstance(
            new PathToRoadyModuleDirectory(
                $pathToDirectoryOfRoadyModules,
                $name
            )
        );
    }
}

