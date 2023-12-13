<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\determinators;

use Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingFile as PathToExistingFileInstance;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingFile;
use \Darling\PHPTextTypes\classes\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory as PathToRoadyModuleDirectoryInstance;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
use \Darling\RoadyRoutes\interfaces\paths\RelativePath;
use \Darling\RoadyRoutes\classes\paths\RelativePath as RelativePathInstance;

/**
 * The RoadyModuleFileSystemPathDeterminatorTestTrait defines common
 * tests for implementations of the
 * RoadyModuleFileSystemPathDeterminator interface.
 *
 * @see RoadyModuleFileSystemPathDeterminator
 *
 */
trait RoadyModuleFileSystemPathDeterminatorTestTrait
{

    /**
     * @var RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
     *                              An instance of a
     *                              RoadyModuleFileSystemPathDeterminator
     *                              implementation to test.
     */
    protected RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator;

    /**
     * Set up an instance of a RoadyModuleFileSystemPathDeterminator
     * implementation to test.
     *
     * This method must set the RoadyModuleFileSystemPathDeterminator
     * implementation instance to be tested via the
     * setRoadyModuleFileSystemPathDeterminatorTestInstance() method.
     *
     * This method may also be used to perform any additional setup
     * required by the implementation being tested.
     *
     * @return void
     *
     * @example
     *
     * ```
     * protected function setUp(): void
     * {
     *     $this->setRoadyModuleFileSystemPathDeterminatorTestInstance(
     *         new \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the RoadyModuleFileSystemPathDeterminator implementation
     * instance to test.
     *
     * @return RoadyModuleFileSystemPathDeterminator
     *
     */
    protected function roadyModuleFileSystemPathDeterminatorTestInstance(): RoadyModuleFileSystemPathDeterminator
    {
        return $this->roadyModuleFileSystemPathDeterminator;
    }

    /**
     * Set the RoadyModuleFileSystemPathDeterminator implementation
     * instance to test.
     *
     * @param RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminatorTestInstance
     *                      An instance of an
     *                      implementation of
     *                      the RoadyModuleFileSystemPathDeterminator
     *                      interface to test.
     *
     * @return void
     *
     */
    protected function setRoadyModuleFileSystemPathDeterminatorTestInstance(
        RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminatorTestInstance
    ): void
    {
        $this->roadyModuleFileSystemPathDeterminator = $roadyModuleFileSystemPathDeterminatorTestInstance;
    }

    private function expectedPathToExistingFile(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RelativePath $relativePath
    ): PathToExistingFile
    {
        $pathToFile = $pathToRoadyModuleDirectory->__toString() . DIRECTORY_SEPARATOR . $relativePath->__toString();
        $fileName = basename($pathToFile);
        $parts = explode(DIRECTORY_SEPARATOR, $pathToFile);
        $safeTextParts = [];
        foreach($parts as $part) {
            if(!empty($part) && $part !== $fileName) {
                $safeTextParts[] = new SafeText(new Text($part));
            }
        }
        $pathToFilesParentDirectory = new PathToExistingDirectoryInstance(
            new SafeTextCollection(...$safeTextParts),
        );
        return new PathToExistingFileInstance(
            $pathToFilesParentDirectory,
            new Name(new Text($fileName)),
        );
    }

    public function test_determinePathToFileInModuleDirectory_returns_expected_PathToExistingFile(): void
    {
        $pathToRoadyModuleDirectory = new PathToRoadyModuleDirectoryInstance(
            new PathToDirectoryOfRoadyModules(
                new PathToExistingDirectoryInstance(
                    $this->safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory()
                ),
            ),
            new Name(new Text('interfaces')),
        );
        $relativePath = new RelativePathInstance(
            new SafeTextCollection(
                new SafeText(new Text('determinators')),
                new SafeText(new Text(basename(__FILE__))),
            )
        );
        $this->assertEquals(
            $this->expectedPathToExistingFile(
                $pathToRoadyModuleDirectory,
                $relativePath,
            ),
            $this->roadyModuleFileSystemPathDeterminatorTestInstance()
                 ->determinePathToFileInModuleDirectory(
                     $pathToRoadyModuleDirectory,
                     $relativePath,
                 ),
            message: $this->testFailedMessage(
                $this->roadyModuleFileSystemPathDeterminatorTestInstance(),
                'determinePathToFileInModuleDirectory',
                'return the expected PathToExistingFile',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public function safeTextCollectionThatMapsToTheRoadyModuleUtilitiesLibrarysTestsDirectory(): SafeTextCollection;

}

