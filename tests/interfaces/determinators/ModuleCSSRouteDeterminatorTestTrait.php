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
     * Set up an instance of a ModuleCSSRouteDeterminator
     * implementation to test.
     *
     * This method must also set the ModuleCSSRouteDeterminator
     * implementation instance to be tested via the
     * setModuleCSSRouteDeterminatorTestInstance() method.
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
     *     $this->setModuleCSSRouteDeterminatorTestInstance(
     *         new ModuleCSSRouteDeterminator()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ModuleCSSRouteDeterminator implementation
     * instance to test.
     *
     * @return ModuleCSSRouteDeterminator
     *
     */
    protected function moduleCSSRouteDeterminatorTestInstance(): ModuleCSSRouteDeterminator
    {
        return $this->moduleCSSRouteDeterminator;
    }

    /**
     * Set the ModuleCSSRouteDeterminator implementation instance
     * to test.
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

    /**
     * Return the expected PositionName that should be used for all
     * CSS Routes.
     *
     * The PositionName will always be "roady-css-stylesheet-links".
     *
     * This position name will correspond to the name of the
     * position placeholder in the template file used to view
     * this routes output.
     *
     * @return PositionName
     *
     */
    private function expectedPositionNameForCSSRoutes(): PositionName
    {
        return new PositionName(
            new NameInstance(new Text('roady-css-stylesheet-links'))
        );
    }

    /**
     * Return a new Route to a CSS file using the specified
     *
     * $moduleName, $requestName, $position, and $relativePath.
     *
     * @param Name $moduleName The Name of the module the CSS file
     *                         belongs to.
     *
     * @param Name $requestName The Name of the only Request that
     *                          the Route will be mapped to.
     *
     * @param Position $position The Position to assign to the Route.
     *
     * @param RelativePath $relativePath The RelativePath to the
     *                                   CSS file in the module's
     *                                   directory.
     *
     *
     * @return Route
     *
     */
    private function newRouteToModuleCSSFile(
        Name $moduleName,
        Name $requestName,
        Position $position,
        RelativePath $relativePath
    ): Route
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

    /**
     * Return a PathToExistingDirectory instance for the expected path
     * to the css directory in the directory indicated by the specified
     * $pathToRoadyModuleDirectory.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The path to the roady module
     *                                   directory where the css
     *                                   directory is expected to be
     *                                   located.
     *
     * @return PathToExistingDirectory
     *
     */
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
    public function expectedRouteCollection(
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
                            str_replace('.css', '', $cssFileNameParts[array_key_first($cssFileNameParts)])
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
     * Test determineCSSRoutes returns the expected RouteCollection.
     *
     * @return void
     *
     * @covers ModuleCSSDeterminator->determineCSSRoutes()
     *
     */
    public function test_determineCSSRoutes_returns_the_expected_RouteCollection(): void
    {
        $pathToRoadyModuleDirectory = $this->pathToRoadyTestModuleDirectory();
        $this->assertEquals(
            $this->expectedRouteCollection($pathToRoadyModuleDirectory),
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

