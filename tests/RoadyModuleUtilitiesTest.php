<?php

namespace Darling\RoadyModuleUtilities\tests;

use \Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitConfigurationTests;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitRandomValues;
use \Darling\PHPUnitTestUtilities\traits\PHPUnitTestMessages;
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

}
