<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\determinators;

use Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use Darling\PHPTextTypes\classes\collections\NameCollection;
use Darling\RoadyRoutes\classes\collections\NamedPositionCollection;
use Darling\RoadyRoutes\classes\identifiers\NamedPosition;
use Darling\RoadyRoutes\classes\identifiers\PositionName;
use Darling\RoadyRoutes\classes\paths\RelativePath;
use Darling\RoadyRoutes\classes\routes\Route;
use Darling\RoadyRoutes\classes\settings\Position;
use DirectoryIterator;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RegexIterator;
use \RecursiveRegexIterator;

/**
 * The ModuleCSSRouteDeterminatorTestTrait defines common tests for
 * implementations of the ModuleCSSRouteDeterminator interface.
 *
 * @see ModuleCSSRouteDeterminator
 *
 */
trait ModuleCSSRouteDeterminatorTestTrait
{

    /**
     * @var ModuleCSSRouteDeterminator $moduleCSSRouteDeterminator
     *                              An instance of a
     *                              ModuleCSSRouteDeterminator
     *                              implementation to test.
     */
    protected ModuleCSSRouteDeterminator $moduleCSSRouteDeterminator;

    /**
     * Set up an instance of a ModuleCSSRouteDeterminator implementation to test.
     *
     * This method must also set the ModuleCSSRouteDeterminator implementation instance
     * to be tested via the setModuleCSSRouteDeterminatorTestInstance() method.
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
     *     $this->setModuleCSSRouteDeterminatorTestInstance(
     *         new \Darling\RoadyModuleUtilities\classes\determinators\ModuleCSSRouteDeterminator()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ModuleCSSRouteDeterminator implementation instance to test.
     *
     * @return ModuleCSSRouteDeterminator
     *
     */
    protected function moduleCSSRouteDeterminatorTestInstance(): ModuleCSSRouteDeterminator
    {
        return $this->moduleCSSRouteDeterminator;
    }

    /**
     * Set the ModuleCSSRouteDeterminator implementation instance to test.
     *
     * @param ModuleCSSRouteDeterminator $moduleCSSRouteDeterminatorTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the ModuleCSSRouteDeterminator
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setModuleCSSRouteDeterminatorTestInstance(
        ModuleCSSRouteDeterminator $moduleCSSRouteDeterminatorTestInstance
    ): void
    {
        $this->moduleCSSRouteDeterminator = $moduleCSSRouteDeterminatorTestInstance;
    }

    private function expectedPositionNameForCSSRoutes(): PositionName
    {
        return new PositionName(
            new NameInstance(new Text('roady-css-stylesheet-links'))
        );
    }

    private function newRouteToModuleCSSFile(Name $moduleName, Name $requestName, Position $position): Route
    {
        return new Route(
           $moduleName,
            new NameCollection($requestName),
            new NamedPositionCollection(
                new NamedPosition(
                    # "roady-css-stylesheet-links" will always be the
                    # name of the position for routes defined for css
                    # stylesheets. This position name will correspond
                    # to the name of the position placeholder in the
                    # template file used to view this routes output.
                    $this->expectedPositionNameForCSSRoutes(),
                    $position,
                ),
            ),
            new RelativePath(new SafeTextCollectionInstance()),
        );
    }

    private function expectedPathToModulesCSSDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): PathToExistingDirectory
    {
        $parts = $pathToRoadyModuleDirectory->pathToExistingDirectory()
                                            ->safeTextCollection()
                                            ->collection();
        $parts[] = new SafeText(new Text('css'));
        return new PathToExistingDirectoryInstance(
            new SafeTextCollectionInstance(...$parts)
        );
    }

    /**
     * Return the RouteCollection that is expected to be returned
     * by the ModuleCSSRouteDeterminator being tested's
     * determineCSSRoutes() method based on the specified
     * PathToRoadyModuleDirectory.
     *
     * @return RouteCollection
     *
     */
    public function expectedCSSRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesCSSDirectory =
            $this->expectedPathToModulesCSSDirectory(
                $pathToRoadyModuleDirectory
            );
        $routes = [];
        if($pathToModulesCSSDirectory->__toString() !== sys_get_temp_dir()) {
            $cssFileNames = [];
            $directory = new RecursiveDirectoryIterator($pathToModulesCSSDirectory->__toString());
            $iterator = new RecursiveIteratorIterator($directory);
            $cssFilePaths = new RegexIterator($iterator, '/^.+\.css$/i', RecursiveRegexIterator::GET_MATCH);
            foreach($cssFilePaths as $cssFilePath) {
                if(
                    is_array($cssFilePath)
                    &&
                    isset($cssFilePath[0])
                    &&
                    is_string($cssFilePath[0])
                    &&
                    file_exists($cssFilePath[0])
                ) {
                    $cssFilePath = $cssFilePath[0];
                    $cssFileName = basename($cssFilePath[0]);
                    $relativePathToCssFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $cssFilePath);
                    $relativePathToCssFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToCssFile);
                    $safeTextForRelativePathToCSSFile = [];
                    foreach($relativePathToCssFileParts as $relativePathPart) {
                        if(!empty($relativePathPart)) {
                            $safeTextForRelativePathToCSSFile[] = new SafeText(new Text($relativePathPart));
                        }
                    }
                    $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToCSSFile));
                    var_dump($relativePathForRoute->__toString());
                }
            }
        }
        /*
        foreach($cssFileNames as $cssFileName) {
            $nameParts = explode('_', $cssFileName);
            var_dump($nameParts);
            $routes[] = $this->newRouteToModuleCSSFile(
                $this->randomNameOfExisitngTestModule(),
                new NameInstance(new Text('for-specfic-request')),
                new Position(rand(PHP_INT_MIN, PHP_INT_MAX)),
            );
        }
        */
        return new RouteCollectionInstance(
           ...$routes
        );
    }

    /**
     * Test that the determinePathToFileInModuleDirectory method
     * returns expected PathToExistingFile.
     *
     * @return void
     *
     * @covers RoadyModuleFileSystemPathDeterminator->determinePathToFileInModuleDirectory()
     *
     */
    public function test_determinePathToFileInModuleDirectory_returns_expected_PathToExistingFile(): void
    {
        $pathToRoadyModuleDirectory = $this->pathToRoadyTestModuleDirectory();
        $this->assertEquals(
            $this->expectedCSSRoutes($pathToRoadyModuleDirectory),
            $this->moduleCSSRouteDeterminatorTestInstance()->determineCSSRoutes($pathToRoadyModuleDirectory),
            message: $this->testFailedMessage(
                $this->moduleCSSRouteDeterminatorTestInstance(),
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

