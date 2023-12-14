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
use \PHPUnit\Framework\TestCase;
use \DirectoryIterator;

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

    public function pathToRoadyTestModuleDirectory(): PathToRoadyModuleDirectory
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
        $testModuleDirectory = new DirectoryIterator($pathToRoadyModuleUtilitiesTestModulesDirectory->__toString());
        $testModuleNames = [];
        foreach($testModuleDirectory as $fileInfo) {
            if($fileInfo->isDir() && !$fileInfo->isDot()) {
                $testModuleNames[] = $fileInfo->getFilename();
            }
        }
        $targetModuleName = $testModuleNames[array_rand($testModuleNames)];
        $pathToDirectoryOfRoadyTestModules = new PathToDirectoryOfRoadyModules(
            $pathToRoadyModuleUtilitiesTestModulesDirectory,
        );
        return new PathToRoadyModuleDirectory(
            $pathToDirectoryOfRoadyTestModules,
            new Name(new Text($targetModuleName)),
        );
    }
}
