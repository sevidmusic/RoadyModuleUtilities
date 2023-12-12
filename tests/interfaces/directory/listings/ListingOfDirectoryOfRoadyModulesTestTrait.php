<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\directory\listings;

use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;

/**
 * The ListingOfDirectoryOfRoadyModulesTestTrait defines common
 * tests for implementations of the ListingOfDirectoryOfRoadyModules
 * interface.
 *
 * @see ListingOfDirectoryOfRoadyModules
 *
 */
trait ListingOfDirectoryOfRoadyModulesTestTrait
{

    /**
     * @var ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModules
     *              An instance of a ListingOfDirectoryOfRoadyModules
     *              implementation to test.
     */
    protected ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModules;

    /**
     * The PathToDirectoryOfRoadyModules instance that is expected
     * to be returned by the ListingOfDirectoryOfRoadyModules being
     * tested's pathToDirectoryOfRoadyModules() method.
     */
    private PathToDirectoryOfRoadyModules $expectedPathToDirectoryOfRoadyModules;

    /**
     * Set up an instance of a ListingOfDirectoryOfRoadyModules
     * implementation to test.
     *
     * This method must set the ListingOfDirectoryOfRoadyModules
     * implementation instance to be tested via the
     * setListingOfDirectoryOfRoadyModulesTestInstance() method.
     *
     * This method must also set the PathToDirectoryOfRoadyModules
     * implementation instance that is expected to be returned by
     * the ListingOfDirectoryOfRoadyModules being tested's
     * pathToDirectoryOfRoadyModules() method via the
     * setExpectedPathToDirectoryOfRoadyModules() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     * public function setUp(): void
     * {
     *     $pathToDirectoryOfRoadyModules = new PathToDirectoryOfRoadyModules(
     *         new PathToExistingDirectory(
     *             $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
     *         ),
     *     );
     *     $this->setExpectedPathToDirectoryOfRoadyModules($pathToDirectoryOfRoadyModules);
     *     $this->setListingOfDirectoryOfRoadyModulesTestInstance(
     *         new ListingOfDirectoryOfRoadyModules(
     *             $pathToDirectoryOfRoadyModules
     *         )
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ListingOfDirectoryOfRoadyModules implementation
     * instance to test.
     *
     * @return ListingOfDirectoryOfRoadyModules
     *
     */
    protected function listingOfDirectoryOfRoadyModulesTestInstance(): ListingOfDirectoryOfRoadyModules
    {
        return $this->listingOfDirectoryOfRoadyModules;
    }

    /**
     * Set the ListingOfDirectoryOfRoadyModules implementation
     * instance to test.
     *
     * @param ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModulesTestInstance
     *                           An instance of an
     *                           implementation of
     *                           the ListingOfDirectoryOfRoadyModules
     *                           interface to test.
     *
     * @return void
     *
     */
    protected function setListingOfDirectoryOfRoadyModulesTestInstance(
        ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModulesTestInstance
    ): void
    {
        $this->listingOfDirectoryOfRoadyModules = $listingOfDirectoryOfRoadyModulesTestInstance;
    }

    /**
     * Set the PathToDirectoryOfRoadyModules that is expected to be
     * returned by the ListingOfDirectoryOfRoadyModules being tested's
     * pathToDirectoryOfRoadyModules() method.
     *
     * @param PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
     *                 The PathToDirectoryOfRoadyModules
     *                 that is expected to be returned by
     *                 the ListingOfDirectoryOfRoadyModules
     *                 being tested's pathToDirectoryOfRoadyModules()
     *                 method.
     *
     * @return void
     */
    protected function setExpectedPathToDirectoryOfRoadyModules(
        PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
    ): void
    {
        $this->expectedPathToDirectoryOfRoadyModules = $pathToDirectoryOfRoadyModules;
    }

    /**
     * Return the PathToDirectoryOfRoadyModules that is expected to be
     * returned by the ListingOfDirectoryOfRoadyModules being tested's
     * pathToDirectoryOfRoadyModules() method.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    protected function expectedPathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules
    {
        return $this->expectedPathToDirectoryOfRoadyModules;
    }

    /**
     * Return the PathToRoadyModuleDirectoryCollection
     * instance that is expected to be returned by the
     * ListingOfDirectoryOfRoadyModules being tested's
     * pathToRoadyModuleDirectoryCollection() method.
     *
     * @return PathToRoadyModuleDirectoryCollection
     *
     */
    private function expectedPathToRoadyModuleDirectoryCollection(): PathToRoadyModuleDirectoryCollection
    {
        $directoryIterator = new \DirectoryIterator(
            $this->expectedPathToDirectoryOfRoadyModules()->__toString()
        );
        $expectedPathToRoadyModuleDirectoryInstances = [];
        foreach($directoryIterator as $fileInfo) {
            if($fileInfo->isDot()) {
                continue;
            }
            if(is_dir($fileInfo->getRealPath())) {
                $expectedPathToRoadyModuleDirectoryInstances[] =
                    new PathToRoadyModuleDirectory(
                        $this->expectedPathToDirectoryOfRoadyModules(),
                        new Name(new Text($fileInfo->getFilename())),
                    );
            }
        }
        return new PathToRoadyModuleDirectoryCollection(
            ...$expectedPathToRoadyModuleDirectoryInstances
        );
    }

    /**
     * Test pathToDirectoryOfRoadyModules() returns the expected
     * PathToDirectoryOfRoadyModules.
     *
     * @return void
     *
     * @covers ListingOfDirectoryOfRoadyModules->pathToDirectoryOfRoadyModules()
     *
     */
    public function test_pathToDirectoryOfRoadyModules_returns_the_expected_PathToDirectoryOfRoadyModules(): void
    {
        $this->assertSame(
            $this->expectedPathToDirectoryOfRoadyModules(),
            $this->listingOfDirectoryOfRoadyModulesTestInstance()
                 ->pathToDirectoryOfRoadyModules(),
            $this->testFailedMessage(
                $this->listingOfDirectoryOfRoadyModulesTestInstance(),
                'pathToDirectoryOfRoadyModules',
                'return the expected PathToDirectoryOfRoadyModules',
            ),
        );
    }

    /**
     * Test pathToRoadyModuleDirectoryCollection() returns the expected
     * PathToRoadyModuleDirectoryCollection.
     *
     * @return void
     *
     * @covers ListingOfDirectoryOfRoadyModules->pathToRoadyModuleDirectoryCollection()
     *
     */
    public function test_pathToRoadyModuleDirectoryCollection_returns_the_expected_PathToRoadyModuleDirectoryCollection(): void
    {
        $this->assertEquals(
            $this->expectedPathToRoadyModuleDirectoryCollection(),
            $this->listingOfDirectoryOfRoadyModulesTestInstance()
                 ->pathToRoadyModuleDirectoryCollection(),
            $this->testFailedMessage(
                $this->listingOfDirectoryOfRoadyModulesTestInstance(),
                'pathToRoadyModuleDirectoryCollection',
                'return the expected PathToRoadyModuleDirectoryCollection',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void;

}

