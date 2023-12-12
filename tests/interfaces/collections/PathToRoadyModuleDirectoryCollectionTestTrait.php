<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\collections;

use \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

/**
 * The PathToRoadyModuleDirectoryCollectionTestTrait
 * defines common tests for implementations of the
 * PathToRoadyModuleDirectoryCollection interface.
 *
 * @see PathToRoadyModuleDirectoryCollection
 *
 */
trait PathToRoadyModuleDirectoryCollectionTestTrait
{

    /**
     * @var PathToRoadyModuleDirectoryCollection $pathToRoadyModuleDirectoryCollection
     *                            An instance of a
     *                            PathToRoadyModuleDirectoryCollection
     *                            implementation to test.
     */
    protected PathToRoadyModuleDirectoryCollection $pathToRoadyModuleDirectoryCollection;


    /**
     * @var array<int, PathToRoadyModuleDirectory> $expectedCollection
     */
    protected array $expectedCollection = [];

    /**
     * Set up an instance of a PathToRoadyModuleDirectoryCollection
     * implementation to test.
     *
     * This method must set the PathToRoadyModuleDirectoryCollection
     * implementation instance to be tested via the
     * setPathToRoadyModuleDirectoryCollectionTestInstance()
     * method.
     *
     * This method must also set the expected collection of
     * PathToRoadyModuleDirectory instances that is expected to be
     * returned by the PathToRoadyModuleDirectoryCollection being
     * tested's collection() method via the setExpectedCollection()
     * method.
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
     *     $collection = [
     *         new PathToRoadyModuleDirectory(
     *             new PathToDirectoryOfRoadyModules(
     *                 new PathToExistingDirectory(
     *                     new SafeTextCollection(),
     *                 ),
     *             ),
     *             new Name(new Text($this->randomChars())),
     *         ),
     *     ];
     *     $this->setExpectedCollection(...$collection);
     *     $this->setPathToRoadyModuleDirectoryCollectionTestInstance(
     *         new PathToRoadyModuleDirectoryCollection(...$collection)
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the PathToRoadyModuleDirectoryCollection implementation
     * instance to test.
     *
     * @return PathToRoadyModuleDirectoryCollection
     *
     */
    protected function pathToRoadyModuleDirectoryCollectionTestInstance(): PathToRoadyModuleDirectoryCollection
    {
        return $this->pathToRoadyModuleDirectoryCollection;
    }

    /**
     * Set the PathToRoadyModuleDirectoryCollection implementation
     * instance to test.
     *
     * @param PathToRoadyModuleDirectoryCollection $pathToRoadyModuleDirectoryCollectionTestInstance
     *                    An instance of an implementation
     *                    of the PathToRoadyModuleDirectoryCollection
     *                    interface to test.
     *
     * @return void
     *
     */
    protected function setPathToRoadyModuleDirectoryCollectionTestInstance(
        PathToRoadyModuleDirectoryCollection $pathToRoadyModuleDirectoryCollectionTestInstance
    ): void
    {
        $this->pathToRoadyModuleDirectoryCollection = $pathToRoadyModuleDirectoryCollectionTestInstance;
    }

    /**
     * Set the expected collection of PathToRoadyModuleDirectory
     * instances.
     *
     * @param PathToRoadyModuleDirectory ...$pathToRoadyModuleDirectories
     *        The PathToRoadyModuleDirectory instances to include in
     *        the collection that is expected to be returned by the
     *        PathToRoadyModuleDirectoryCollection being tested's
     *        collection() method.
     *
     *
     * @return void
     *
     */
    protected function setExpectedCollection(PathToRoadyModuleDirectory ...$pathToRoadyModuleDirectories): void
    {
        foreach ($pathToRoadyModuleDirectories as $pathToRoadyModuleDirectory) {
            $this->expectedCollection[] = $pathToRoadyModuleDirectory;
        }
    }

    /**
     * Return the expected collection of PathToRoadyModuleDirectory
     * instances.
     *
     * @return array<int, PathToRoadyModuleDirectory>
     *
     */
    protected function expectedCollection(): array
    {
        return $this->expectedCollection;
    }

    /**
     * Test collection returns the expected collection of
     * PathToRoadyModuleDirectory instances.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectoryCollection->collection()
     *
     */
    public function test_collection_returns_the_expected_collection_of_PathToRoadyModuleDirectory_instances(): void
    {
        $this->assertSame(
            $this->expectedCollection(),
            $this->pathToRoadyModuleDirectoryCollectionTestInstance()->collection(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryCollectionTestInstance(),
                'collection',
                'return the expected collection of PathToRoadyModuleDirectory instances',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void;

}

