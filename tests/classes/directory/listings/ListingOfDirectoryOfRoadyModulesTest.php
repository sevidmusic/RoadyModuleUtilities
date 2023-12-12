<?php

namespace Darling\RoadyModuleUtilities\tests\classes\directory\listings;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\RoadyModuleUtilities\classes\directory\listings\ListingOfDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\directory\listings\ListingOfDirectoryOfRoadyModulesTestTrait;

class ListingOfDirectoryOfRoadyModulesTest extends RoadyModuleUtilitiesTest
{

    /**
     * The ListingOfDirectoryOfRoadyModulesTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules
     * interface.
     *
     * @see ListingOfDirectoryOfRoadyModulesTestTrait
     *
     */
    use ListingOfDirectoryOfRoadyModulesTestTrait;

    public function setUp(): void
    {
        $pathToDirectoryOfRoadyModules = new PathToDirectoryOfRoadyModules(
            new PathToExistingDirectory(
                $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
            ),
        );
        $this->setExpectedPathToDirectoryOfRoadyModules($pathToDirectoryOfRoadyModules);
        $this->setListingOfDirectoryOfRoadyModulesTestInstance(
            new ListingOfDirectoryOfRoadyModules(
                $pathToDirectoryOfRoadyModules
            )
        );
    }
}

