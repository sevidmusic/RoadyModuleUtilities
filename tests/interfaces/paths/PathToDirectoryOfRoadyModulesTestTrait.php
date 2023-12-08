<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\paths;

use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;

/**
 * The PathToDirectoryOfRoadyModulesTestTrait defines common tests for
 * implementations of the PathToDirectoryOfRoadyModules interface.
 *
 * @see PathToDirectoryOfRoadyModules
 *
 */
trait PathToDirectoryOfRoadyModulesTestTrait
{

    /**
     * @var PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
     *                              An instance of a
     *                              PathToDirectoryOfRoadyModules
     *                              implementation to test.
     */
    private PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules;


    /**
     * @var PathToExistingDirectory The PathToExistingDirectory
     *                              instance that is expected to
     *                              be returned by the
     *                              PathToDirectoryOfRoadyModules
     *                              being tested's
     *                              pathToExistingDirectory() method.
     */
    private PathToExistingDirectory $expectedPathToExistingDirectory;

    /**
     * Set up an instance of a PathToDirectoryOfRoadyModules
     * implementation to test.
     *
     * This method must set the PathToDirectoryOfRoadyModules
     * implementation instance to be tested via the
     * setPathToDirectoryOfRoadyModulesTestInstance() method.
     *
     * This method must also set the PathToExistingDirectory
     * instance that is expected to be returned by the
     * PathToDirectoryOfRoadyModules being tested's
     * pathToExistingDirectory() method via the
     * setExpectedPathToExistingDirectory() method.
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
     *     $this->setExpectedPathToExistingDirectory($pathToExistingDirectory);
     *     $this->setPathToDirectoryOfRoadyModulesTestInstance(
     *         new PathToDirectoryOfRoadyModules($pathToExistingDirectory)
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the PathToDirectoryOfRoadyModules implementation
     * instance to test.
     *
     * @return PathToDirectoryOfRoadyModules
     *
     */
    protected function pathToDirectoryOfRoadyModulesTestInstance(): PathToDirectoryOfRoadyModules
    {
        return $this->pathToDirectoryOfRoadyModules;
    }

    /**
     * Set the PathToDirectoryOfRoadyModules implementation instance
     * to test.
     *
     * @param PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModulesTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the PathToDirectoryOfRoadyModules
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setPathToDirectoryOfRoadyModulesTestInstance(
        PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModulesTestInstance
    ): void
    {
        $this->pathToDirectoryOfRoadyModules = $pathToDirectoryOfRoadyModulesTestInstance;
    }

    /**
     * Set the PathToExistingDirectory instance that is expected to
     * be returned by the PathToDirectoryOfRoadyModules instance
     * being tested's pathToExistingDirectory() method.
     *
     * @return void
     *
     */
    protected function setExpectedPathToExistingDirectory(PathToExistingDirectory $pathToExistingDirectory): void
    {
        $this->expectedPathToExistingDirectory = $pathToExistingDirectory;
    }

    /**
     * Return the PathToExistingDirectory instance that is expected to
     * be returned by the PathToDirectoryOfRoadyModules instance
     * being tested's pathToExistingDirectory() method.
     *
     * @return PathToExistingDirectory
     *
     */
    protected function expectedPathToExistingDirectory(): PathToExistingDirectory
    {
        return $this->expectedPathToExistingDirectory;
    }

    /**
     * Test pathToExistingDirectory returns expected
     * PathToExistingDirectory.
     *
     * @return void
     *
     * @covers PathToDirectoryOfRoadyModules->pathToExistingDirectory()
     *
     */
    public function test_pathToExistingDirectory_returns_expected_PathToExistingDirectory(): void
    {
        $this->assertSame(
            $this->expectedPathToExistingDirectory(),
            $this->pathToDirectoryOfRoadyModulesTestInstance()->pathToExistingDirectory(),
            $this->testFailedMessage(
                $this->pathToDirectoryOfRoadyModulesTestInstance(),
                'pathToExistingDirectory',
                'return the expected PathToDirectoryOfRoadyModules'
            ),
        );
    }

    /**
     * Test __toString() returns the same path returned by assigned
     * PathToExistingDirectory's __toString() method.
     *
     * @return void
     *
     * @covers PathToDirectoryOfRoadyModules->__toString()
     *
     */
    public function test__toString_returns_the_same_path_returned_by_assigned_PathToExistingDirectorys___toString_method(): void
    {
        $this->assertSame(
            $this->expectedPathToExistingDirectory()->__toString(),
            $this->pathToDirectoryOfRoadyModulesTestInstance()->__toString(),
            $this->testFailedMessage(
                $this->pathToDirectoryOfRoadyModulesTestInstance(),
                '__toString',
                'return the same path returned by assigned ' .
                'PathToExistingDirectory\'s __toString() method.',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void;

}

