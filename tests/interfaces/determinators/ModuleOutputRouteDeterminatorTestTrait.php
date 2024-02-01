<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\determinators;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\classes\collections\NameCollection;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleOutputRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\NamedPositionCollection;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\classes\identifiers\NamedPosition;
use \Darling\RoadyRoutes\classes\identifiers\PositionName;
use \Darling\RoadyRoutes\classes\paths\RelativePath;
use \Darling\RoadyRoutes\classes\routes\Route;
use \Darling\RoadyRoutes\classes\settings\Position;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

/**
 * The ModuleOutputRouteDeterminatorTestTrait defines common tests for
 * implementations of the ModuleOutputRouteDeterminator interface.
 *
 * @see ModuleOutputRouteDeterminator
 *
 */
trait ModuleOutputRouteDeterminatorTestTrait
{

    /**
     * @var ModuleOutputRouteDeterminator $moduleOutputRouteDeterminator
     *                              An instance of a
     *                              ModuleOutputRouteDeterminator
     *                              implementation to test.
     */
    protected ModuleOutputRouteDeterminator $moduleOutputRouteDeterminator;

    /**
     * Set up an instance of a ModuleOutputRouteDeterminator
     * implementation to test.
     *
     * This method must also set the ModuleOutputRouteDeterminator
     * implementation instance to be tested via the
     * setModuleOutputRouteDeterminatorTestInstance() method.
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
     *     $this->setModuleOutputRouteDeterminatorTestInstance(
     *         new ModuleOutputRouteDeterminator()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ModuleOutputRouteDeterminator implementation
     * instance to test.
     *
     * @return ModuleOutputRouteDeterminator
     *
     */
    protected function moduleOutputRouteDeterminatorTestInstance(): ModuleOutputRouteDeterminator
    {
        return $this->moduleOutputRouteDeterminator;
    }

    /**
     * Set the ModuleOutputRouteDeterminator implementation instance
     * to test.
     *
     * @param ModuleOutputRouteDeterminator $moduleOutputRouteDeterminatorTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the ModuleOutputRouteDeterminator
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setModuleOutputRouteDeterminatorTestInstance(
        ModuleOutputRouteDeterminator $moduleOutputRouteDeterminatorTestInstance
    ): void
    {
        $this->moduleOutputRouteDeterminator = $moduleOutputRouteDeterminatorTestInstance;
    }

    /**
     * Return a new Route to a Output file using the specified
     *
     * $moduleName, $requestName, $position, and $relativePath.
     *
     * @param Name $moduleName The Name of the module the Output file
     *                         belongs to.
     *
     * @param Name $requestName The Name of the only Request that
     *                          the Route will be mapped to.
     *
     * @param Position $position The Position to assign to the Route.
     *
     * @param RelativePath $relativePath The RelativePath to the
     *                                   Output file in the module's
     *                                   directory.
     *
     *
     * @return Route
     *
     */
    private function newRouteToModuleOutputFile(
        Name $moduleName,
        Name $requestName,
        PositionName $positionName,
        Position $position,
        RelativePath $relativePath
    ): Route
    {
        return new Route(
           $moduleName,
            new NameCollection($requestName),
            new NamedPositionCollection(
                new NamedPosition(
                    $positionName,
                    $position,
                ),
            ),
            $relativePath,
        );
    }

    /**
     * Return a PathToExistingDirectory instance for the expected path
     * to the output directory in the directory indicated by the specified
     * $pathToRoadyModuleDirectory.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The path to the roady module
     *                                   directory where the output
     *                                   directory is expected to be
     *                                   located.
     *
     * @return PathToExistingDirectory
     *
     */
    private function expectedPathToModulesOutputDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): PathToExistingDirectory
    {
        $parts = $pathToRoadyModuleDirectory->pathToExistingDirectory()
                                            ->safeTextCollection()
                                            ->collection();
        $parts[] = new SafeText(new Text('output'));
        return new PathToExistingDirectoryInstance(
            new SafeTextCollectionInstance(...$parts)
        );
    }

    /**
     * Return the RouteCollection that is expected to be returned
     * by the ModuleOutputRouteDeterminator being tested's
     * determineOutputRoutes() method based on the specified
     * PathToRoadyModuleDirectory.
     *
     * @return RouteCollection
     *
     */
    public function expectedRouteCollection(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesOutputDirectory =
            $this->expectedPathToModulesOutputDirectory(
                $pathToRoadyModuleDirectory
            );
        $routes = [];
        if($pathToModulesOutputDirectory->__toString() !== sys_get_temp_dir()) {
            $outputFileNames = [];
            $directory = new RecursiveDirectoryIterator($pathToModulesOutputDirectory->__toString());
            $iterator = new RecursiveIteratorIterator($directory);
            $outputFilePaths = new RegexIterator($iterator, '/^.+\.(php|html)$/i', RecursiveRegexIterator::GET_MATCH);
            foreach($outputFilePaths as $outputFilePath) {
                if(
                    is_array($outputFilePath)
                    &&
                    isset($outputFilePath[0])
                    &&
                    is_string($outputFilePath[0])
                    &&
                    file_exists($outputFilePath[0])
                ) {
                    // PATH TO Output FILE
                    $pathToOutputFile = $outputFilePath[0];

                    // REQUEST NAME
                    $outputFileName = str_replace(['.php', '.html'], '', basename($pathToOutputFile));
                    $outputFileNameParts = explode('_', $outputFileName);
                    $requestName = new NameInstance(
                        new Text(
                            $outputFileNameParts[array_key_first($outputFileNameParts)]
                            ??
                            $outputFileName
                        )
                    );

                    // POSITION
                    $position = new Position(
                        floatval(
                            strval($outputFileNameParts[array_key_last($outputFileNameParts)] ?? 0)
                        )
                    );

                    // POSITION NAME
                    array_shift($outputFileNameParts);
                    array_pop($outputFileNameParts);
                    $positionNameString = implode('', $outputFileNameParts);
                    $positionName = new PositionName(new NameInstance(new Text((empty($positionNameString) ? 'roady-ui-main-content' : $positionNameString))));

                    // RELATIVE PATH
                    $relativePathToOutputFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $pathToOutputFile);
                    $relativePathToOutputFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToOutputFile);
                    $safeTextForRelativePathToOutputFile = [];
                    foreach($relativePathToOutputFileParts as $relativePathPart) {
                        if(!empty($relativePathPart)) {
                            $safeTextForRelativePathToOutputFile[] = new SafeText(new Text($relativePathPart));
                        }
                    }
                    $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToOutputFile));
                    $routes[] = $this->newRouteToModuleOutputFile(
                        $pathToRoadyModuleDirectory->name(),
                        $requestName,
                        $positionName,
                        $position,
                        $relativePathForRoute,
                    );
                }
            }
        }
        return new RouteCollectionInstance(
           ...$routes
        );
    }

    /**
     * Test determineOutputRoutes returns the expected RouteCollection.
     *
     * @return void
     *
     * @covers ModuleOutputDeterminator->determineOutputRoutes()
     *
     */
    public function test_determineOutputRoutes_returns_the_expected_RouteCollection(): void
    {
        $pathToRoadyModuleDirectory = $this->pathToRoadyTestModuleDirectory();
        $this->assertEquals(
            $this->expectedRouteCollection($pathToRoadyModuleDirectory),
            $this->moduleOutputRouteDeterminatorTestInstance()->determineOutputRoutes($pathToRoadyModuleDirectory),
            message: $this->testFailedMessage(
                $this->moduleOutputRouteDeterminatorTestInstance(),
                'determinePathToFileInModuleDirectory',
                'return the expected PathToExistingFile',
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public function pathToRoadyTestModuleDirectory(): PathToRoadyModuleDirectory;
    abstract public function randomNameOfExisitngTestModule(): Name;

}

