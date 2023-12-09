<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\paths;

use Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\PHPTextTypes\classes\strings\SafeText;

/**
 * The PathToRoadyModuleDirectoryTestTrait defines common tests for
 * implementations of the PathToRoadyModuleDirectory interface.
 *
 * @see PathToRoadyModuleDirectory
 *
 */
trait PathToRoadyModuleDirectoryTestTrait
{

    /**
     * @var PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                              An instance of a
     *                              PathToRoadyModuleDirectory
     *                              implementation to test.
     */
    protected PathToRoadyModuleDirectory $pathToRoadyModuleDirectory;

    /**
     * The PathToDirectoryOfRoadyModules instance that is
     * expected to be returned by the PathToRoadyModuleDirectory
     * instance being tested's pathToDirectoryOfRoadyModules() method.
     */
    protected PathToDirectoryOfRoadyModules $expectedPathToDirectoryOfRoadyModules;

    /**
     * The Name instance that is expected to be returned by
     * the PathToDirectoryOfRoadyModules instance being tested's
     * name() method.
     */
    protected Name $expectedName;

    /**
     * Set up an instance of a PathToRoadyModuleDirectory
     * implementation to test.
     *
     * This method must set the PathToRoadyModuleDirectory
     * implementation instance to be tested via the
     * setPathToRoadyModuleDirectoryTestInstance() method.
     *
     * This method must also set the PathToDirectoryOfRoadyModules
     * instance that is expected to be returned by the
     * PathToRoadyModuleDirectory instance being tested's
     * pathToDirectoryOfRoadyModules() method via the
     * setExpectedPathToDirectoryOfRoadyModules() method.
     *
     * This method must also set the Name instance that is expected
     * to be returned by the PathToDirectoryOfRoadyModules instance
     * being tested's name() method via the setExpectedName() method.
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
     *     $pathToExistingDirectory = new PathToExistingDirectory(
     *         $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
     *     );
     *     $pathToNonExistingDirectory = new PathToExistingDirectory(
     *         $this->safeTextCollectionThatMapsToADirectoryThatDoesNotExist()
     *     );
     *     $testDirectory = (rand(0, 1) ? $pathToExistingDirectory : $pathToNonExistingDirectory);
     *     $pathToDirectoryOfRoadyModules = new PathToDirectoryOfRoadyModules(
     *             $testDirectory
     *     );
     *     $this->setExpectedPathToDirectoryOfRoadyModules($pathToDirectoryOfRoadyModules);
     *     $name = new Name(new Text(rand(0, 1) ? 'interfaces' : $this->randomChars()));
     *     $this->setExpectedName($name);
     *     $this->setPathToRoadyModuleDirectoryTestInstance(
     *         new PathToRoadyModuleDirectory(
     *             $pathToDirectoryOfRoadyModules,
     *             $name
     *         )
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the PathToRoadyModuleDirectory implementation instance
     * to test.
     *
     * @return PathToRoadyModuleDirectory
     *
     */
    protected function pathToRoadyModuleDirectoryTestInstance(): PathToRoadyModuleDirectory
    {
        return $this->pathToRoadyModuleDirectory;
    }

    /**
     * Set the PathToRoadyModuleDirectory implementation instance to
     * test.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectoryTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the PathToRoadyModuleDirectory
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setPathToRoadyModuleDirectoryTestInstance(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectoryTestInstance
    ): void
    {
        $this->pathToRoadyModuleDirectory = $pathToRoadyModuleDirectoryTestInstance;
    }

    /**
     * Set the PathToDirectoryOfRoadyModules instance that is
     * expected to be returned by the PathToRoadyModuleDirectory
     * instance being tested's pathToDirectoryOfRoadyModules() method.
     *
     * @param PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
     *                     The PathToDirectoryOfRoadyModules
     *                     instance that is expected to be
     *                     returned by the PathToRoadyModuleDirectory
     *                     instance being tested's
     *                     pathToDirectoryOfRoadyModules() method.
     *
     * @return void
     *
     */
    protected function setExpectedPathToDirectoryOfRoadyModules(PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules): void
    {
        $this->expectedPathToDirectoryOfRoadyModules = $pathToDirectoryOfRoadyModules;
    }

    /**
     * Set the Name instance that is expected to be returned
     * by the PathToRoadyModuleDirectory instance being tested's
     * name() method.
     *
     * @param Name $name The Name instance that is expected to be
     *                   returned by the PathToRoadyModuleDirectory
     *                   instance being tested's name() method.
     *
     * @return void
     *
     */
    protected function setExpectedName(Name $name): void
    {
        $this->expectedName = $name;
    }

    /**
     * Return the PathToDirectoryOfRoadyModules instance that is
     * expected to be returned by the PathToRoadyModuleDirectory
     * instance being tested's pathToDirectoryOfRoadyModules() method.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    protected function expectedPathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules
    {
        return $this->expectedPathToDirectoryOfRoadyModules;
    }

    /**
     * Return the Name instance that is expected to be returned
     * by the PathToRoadyModuleDirectory instance being tested's
     * name() method.
     *
     * @return Name
     *
     */
    protected function expectedName(): Name
    {
        return $this->expectedName;
    }

    /**
     * Return the PathToExistingDirectory instance that is
     * expected to be returned by the PathToRoadyModuleDirectory
     * instance being tested's pathToExistingDirectory() method.
     *
     * If a path to an existing directory can be formed by combining
     * the path defined by the expectedPathToDirectoryOfRoadyModules()
     * and expectedName() then the PathToExistingDirectory will define
     * a path to that directory.
     *
     * Otherwise, it will define a path to the system's temporary
     * directory.
     *
     * @return PathToExistingDirectory
     *
     */
    protected function expectedPathToExistingDirectory(): PathToExistingDirectory
    {
        $pathParts = $this->expectedPathToDirectoryOfRoadyModules()
                          ->pathToExistingDirectory()
                          ->safeTextCollection()
                          ->collection();
        $pathParts[] = new SafeText($this->expectedName());
        $pathToExistingDirectory = new PathToExistingDirectoryInstance(
            new SafeTextCollection(
                ...$pathParts
            ),
        );
        return $pathToExistingDirectory;
    }

    /**
     * Test pathToDirectoryOfRoadyModules() returns the expected
     * PathToDirectoryOfRoadyModules.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectory->pathToDirectoryOfRoadyModules()
     *
     */
    public function test_pathToDirectoryOfRoadyModules_returns_expected_PathToDirectoryOfRoadyModules(): void
    {
        $this->assertSame(
            $this->expectedPathToDirectoryOfRoadyModules(),
            $this->pathToRoadyModuleDirectoryTestInstance()->pathToDirectoryOfRoadyModules(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryTestInstance(),
                'pathToDirectoryOfRoadyModules',
                'return the expected PathToDirectoryOfRoadyModules',
            ),
        );
    }

    /**
     * Test name returns the expected Name.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectory->name()
     *
     */
    public function test_name_returns_the_expected_Name(): void
    {
        $this->assertSame(
            $this->expectedName(),
            $this->pathToRoadyModuleDirectoryTestInstance()->name(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryTestInstance(),
                'name',
                'return the expected Name',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;

    /**
     * Test pathToExistingDirectory returns the expected
     * PathToExistingDirectory instance.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectory->pathToExistingDirectory()
     *
     */
    public function test_pathToExistingDirectory_returns_the_expected_PathToExistingDirectory_instance(): void
    {
        $this->assertEquals(
            $this->expectedPathToExistingDirectory(),
            $this->pathToRoadyModuleDirectoryTestInstance()->pathToExistingDirectory(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryTestInstance(),
                'pathToExistingDirectory',
                'return the expected PathToExistingDirectory',
            ),
        );
    }

    /**
     * Test __toString() returns the same path returned by the
     * expected PathToExistingDirectory instances __toString()
     * method.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectory->__toString()
     *
     */
    public function test___toString_returns_the_same_path_returned_by_the_expected_PathToExistingDirectory_instances___toString_method(): void
    {
        $this->assertEquals(
            $this->expectedPathToExistingDirectory()->__toString(),
            $this->pathToRoadyModuleDirectoryTestInstance()->__toString(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryTestInstance(),
                'pathToExistingDirectory',
                'return the same string returned by the expected ' .
                'PathToExistingDirectory instance\'s __toString() ' .
                'method',
            ),
        );
    }

    /**
     * Test __toString() returns the same path returned by the
     * assigned PathToExistingDirectory instances __toString()
     * method.
     *
     * @return void
     *
     * @covers PathToRoadyModuleDirectory->__toString()
     *
     */
    public function test___toString_returns_the_same_path_returned_by_the_assigned_PathToExistingDirectory_instances___toString_method(): void
    {
        $this->assertEquals(
            $this->pathToRoadyModuleDirectoryTestInstance()->pathToExistingDirectory()->__toString(),
            $this->pathToRoadyModuleDirectoryTestInstance()->__toString(),
            $this->testFailedMessage(
                $this->pathToRoadyModuleDirectoryTestInstance(),
                'pathToExistingDirectory',
                'return the same string returned by the assigned ' .
                'PathToExistingDirectory instance\'s __toString() ' .
                'method',
            ),
        );
    }
}

