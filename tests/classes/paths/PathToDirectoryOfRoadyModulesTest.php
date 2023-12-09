<?php

namespace Darling\RoadyModuleUtilities\tests\classes\paths;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\paths\PathToDirectoryOfRoadyModulesTestTrait;

class PathToDirectoryOfRoadyModulesTest extends RoadyModuleUtilitiesTest
{

    /**
     * The PathToDirectoryOfRoadyModulesTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules
     * interface.
     *
     * @see PathToDirectoryOfRoadyModulesTestTrait
     *
     */
    use PathToDirectoryOfRoadyModulesTestTrait;

    public function setUp(): void
    {
        $pathToExistingDirectory = new PathToExistingDirectory(
            $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
        );
        $pathToNonExistingDirectory = new PathToExistingDirectory(
            $this->safeTextCollectionThatMapsToADirectoryThatDoesNotExist()
        );
        $testDirectory = (rand(0, 1) ? $pathToExistingDirectory : $pathToNonExistingDirectory);
        $this->setExpectedPathToExistingDirectory($pathToExistingDirectory);
        $this->setPathToDirectoryOfRoadyModulesTestInstance(
            new PathToDirectoryOfRoadyModules($pathToExistingDirectory)
        );
    }
}

