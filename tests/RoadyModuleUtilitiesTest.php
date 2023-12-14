<?php

namespace Darling\RoadyModuleUtilities\tests;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitConfigurationTests;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitRandomValues;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitTestMessages;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \DirectoryIterator;
use \PHPUnit\Framework\TestCase;

/**
 * Defines common methods that may be useful to all
 * RoadyModuleUtilities test classes.
 *
 * All RoadyModuleUtilities test classes must extend from this class.
 *
 */
class RoadyModuleUtilitiesTest extends TestCase
{
    use PHPUnitConfigurationTests;
    use PHPUnitTestMessages;
    use PHPUnitRandomValues;

    /**
     * Return a SafeTextCollection that maps to a directory that
     * does not exist.
     *
     * @return SafeTextCollection
     *
     */
    public function safeTextCollectionThatMapsToADirectoryThatDoesNotExist(): SafeTextCollection
    {
        return new SafeTextCollection(
            new SafeText(new Name(new Text($this->randomChars()))),
            new SafeText(new Name(new Text($this->randomChars()))),
            new SafeText(new Name(new Text($this->randomChars()))),
        );
    }

    /**
     * Return a SafeTextCollection that maps to a directory that
     * does exist.
     *
     * @return SafeTextCollection
     *
     */
    public function safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory(): SafeTextCollection
    {
        $currentDirectoryPathParts = explode(
            DIRECTORY_SEPARATOR,
            __DIR__
        );
        $safeTextPartsToExistingDirectoryPath = [];
        foreach($currentDirectoryPathParts as $pathPart) {
            if(!empty($pathPart)) {
                $safeTextPartsToExistingDirectoryPath[] =
                    new SafeText(
                        new Name(new Text($pathPart))
                    );
            }
        }
        return new SafeTextCollection(
            ...$safeTextPartsToExistingDirectoryPath
        );
    }

    /**
     * Randomly select and return a path to one of the existing test
     * modules located in the RoadyModuleUtilities library's directory
     * of test modules.
     *
     * @return PathToRoadyModuleDirectory
     *
     */
    public function pathToRoadyTestModuleDirectory(): PathToRoadyModuleDirectory
    {
        return new PathToRoadyModuleDirectory(
            $this->pathToDirectoryOfRoadyTestModules(),
            $this->randomNameOfExisitngTestModule(),
        );
    }

    /**
     * Return the path to the RoadyModuleUtilities library's
     * directory of test modules.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    public function pathToDirectoryOfRoadyTestModules(): PathToDirectoryOfRoadyModules
    {
        $partsOfPathToRoadyModuleUtilitiesTestsDirectory =
            $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory();
        $partsOfPathToRoadyModuleUtilitiesTestModulesDirectory = [];
        foreach(
            $partsOfPathToRoadyModuleUtilitiesTestsDirectory->collection()
            as
            $part
        )
        {
            $partsOfPathToRoadyModuleUtilitiesTestModulesDirectory[] =
                $part;
        }
        $partsOfPathToRoadyModuleUtilitiesTestModulesDirectory[] =
            new SafeText(new Text('modules'));
        $safeTextCollectionOfPartsOfPathToRoadyModuleUtilitiesTestModulesDirectory =
            new SafeTextCollection(
                ...$partsOfPathToRoadyModuleUtilitiesTestModulesDirectory
            );
        $pathToRoadyModuleUtilitiesTestModulesDirectory =
            new PathToExistingDirectory(
                $safeTextCollectionOfPartsOfPathToRoadyModuleUtilitiesTestModulesDirectory
            );
        return new PathToDirectoryOfRoadyModules(
            $pathToRoadyModuleUtilitiesTestModulesDirectory,
        );
    }


    /**
     * Randomly select one of the existing RoadyMduleUtilities
     * library's test modules and return it's name.
     *
     * @return Name
     *
     */
    public function randomNameOfExisitngTestModule(): Name
    {
        $testModuleDirectory = new DirectoryIterator($this->pathToDirectoryOfRoadyTestModules()->__toString());
        $testModuleNames = [];
        foreach($testModuleDirectory as $fileInfo) {
            if($fileInfo->isDir() && !$fileInfo->isDot()) {
                $testModuleNames[] = $fileInfo->getFilename();
            }
        }
        return new Name(new Text($testModuleNames[array_rand($testModuleNames)]));
    }

}
