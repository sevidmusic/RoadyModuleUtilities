<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\determinators;

use Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use Darling\PHPTextTypes\classes\collections\NameCollection;
use Darling\RoadyRoutes\classes\collections\NamedPositionCollection;
use Darling\RoadyRoutes\classes\identifiers\NamedPosition;
use Darling\RoadyRoutes\classes\identifiers\PositionName;
use Darling\RoadyRoutes\classes\paths\RelativePath;
use Darling\RoadyRoutes\classes\routes\Route;
use Darling\RoadyRoutes\classes\settings\Position;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

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
        /**
         * "roady-css-stylesheet-links" will always be the name of the
         * position for routes defined for css stylesheets.
         *
         * This position name will correspond to the name of the
         * position placeholder in the template file used to view
         * this routes output.
         */
        return new PositionName(
            new NameInstance(new Text('roady-css-stylesheet-links'))
        );
    }

    private function newRouteToModuleCSSFile(Name $moduleName, Name $requestName, Position $position, RelativePath $relativePath): Route
    {
        return new Route(
           $moduleName,
            new NameCollection($requestName),
            new NamedPositionCollection(
                new NamedPosition(
                    $this->expectedPositionNameForCSSRoutes(),
                    $position,
                ),
            ),
            $relativePath,
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
                    // PATH TO CSS FILE
                    $pathToCssFile = $cssFilePath[0];

                    // REQUEST NAME
                    $cssFileName = basename($pathToCssFile);
                    $cssFileNameParts = explode('_', $cssFileName);
                    $requestName = new NameInstance(
                        new Text(
                            $cssFileNameParts[array_key_first($cssFileNameParts)]
                            ??
                            str_replace('.css', '', $cssFileName)
                        )
                    );

                    // POSITION
                    $position = new Position(
                        floatval(
                            str_replace(
                                '.css',
                                '',
                                strval($cssFileNameParts[array_key_last($cssFileNameParts)] ?? 0)
                            )
                        )
                    );

                    // RELATIVE PATH
                    $relativePathToCssFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $pathToCssFile);
                    $relativePathToCssFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToCssFile);
                    $safeTextForRelativePathToCSSFile = [];
                    foreach($relativePathToCssFileParts as $relativePathPart) {
                        if(!empty($relativePathPart)) {
                            $safeTextForRelativePathToCSSFile[] = new SafeText(new Text($relativePathPart));
                        }
                    }
                    $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToCSSFile));
                    $routes[] = $this->newRouteToModuleCSSFile(
                        $pathToRoadyModuleDirectory->name(),
                        $requestName,
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
        foreach($this->expectedCSSRoutes($pathToRoadyModuleDirectory)->collection() as $r) {
            var_dump([$r->moduleName()->__toString(), $r->relativePath()->__toString()]);
        }
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

