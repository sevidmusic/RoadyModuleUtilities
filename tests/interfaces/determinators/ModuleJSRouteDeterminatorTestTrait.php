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
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleJSRouteDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

/**
 * The ModuleJSRouteDeterminatorTestTrait defines common tests for
 * implementations of the ModuleJSRouteDeterminator interface.
 *
 * @see ModuleJSRouteDeterminator
 *
 */
trait ModuleJSRouteDeterminatorTestTrait
{

    /**
     * @var ModuleJSRouteDeterminator $moduleJSRouteDeterminator
     *                              An instance of a
     *                              ModuleJSRouteDeterminator
     *                              implementation to test.
     */
    protected ModuleJSRouteDeterminator $moduleJSRouteDeterminator;

    /**
     * Set up an instance of a ModuleJSRouteDeterminator
     * implementation to test.
     *
     * This method must also set the ModuleJSRouteDeterminator
     * implementation instance to be tested via the
     * setModuleJSRouteDeterminatorTestInstance() method.
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
     *     $this->setModuleJSRouteDeterminatorTestInstance(
     *         new ModuleJSRouteDeterminator()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ModuleJSRouteDeterminator implementation
     * instance to test.
     *
     * @return ModuleJSRouteDeterminator
     *
     */
    protected function moduleJSRouteDeterminatorTestInstance(): ModuleJSRouteDeterminator
    {
        return $this->moduleJSRouteDeterminator;
    }

    /**
     * Set the ModuleJSRouteDeterminator implementation instance
     * to test.
     *
     * @param ModuleJSRouteDeterminator $moduleJSRouteDeterminatorTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the ModuleJSRouteDeterminator
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setModuleJSRouteDeterminatorTestInstance(
        ModuleJSRouteDeterminator $moduleJSRouteDeterminatorTestInstance
    ): void
    {
        $this->moduleJSRouteDeterminator = $moduleJSRouteDeterminatorTestInstance;
    }

    /**
     * Return the expected PositionName that should be used for all
     * JS Routes.
     *
     * The PositionName will always be "roady-js-script-tags".
     *
     * This position name will correspond to the name of the
     * position placeholder in the template file used to view
     * this routes output.
     *
     * @return PositionName
     *
     */
    private function expectedPositionNameForJSRoutes(): PositionName
    {
        return new PositionName(
            new NameInstance(new Text('roady-js-script-tags'))
        );
    }

    /**
     * Return a new Route to a JS file using the specified
     *
     * $moduleName, $requestName, $position, and $relativePath.
     *
     * @param Name $moduleName The Name of the module the JS file
     *                         belongs to.
     *
     * @param Name $requestName The Name of the only Request that
     *                          the Route will be mapped to.
     *
     * @param Position $position The Position to assign to the Route.
     *
     * @param RelativePath $relativePath The RelativePath to the
     *                                   JS file in the module's
     *                                   directory.
     *
     *
     * @return Route
     *
     */
    private function newRouteToModuleJSFile(
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
                    $this->expectedPositionNameForJSRoutes(),
                    $position,
                ),
            ),
            $relativePath,
        );
    }

    /**
     * Return a PathToExistingDirectory instance for the expected path
     * to the js directory in the directory indicated by the specified
     * $pathToRoadyModuleDirectory.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The path to the roady module
     *                                   directory where the js
     *                                   directory is expected to be
     *                                   located.
     *
     * @return PathToExistingDirectory
     *
     */
    private function expectedPathToModulesJSDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): PathToExistingDirectory
    {
        $parts = $pathToRoadyModuleDirectory->pathToExistingDirectory()
                                            ->safeTextCollection()
                                            ->collection();
        $parts[] = new SafeText(new Text('js'));
        return new PathToExistingDirectoryInstance(
            new SafeTextCollectionInstance(...$parts)
        );
    }

    /**
     * Return the RouteCollection that is expected to be returned
     * by the ModuleJSRouteDeterminator being tested's
     * determineJSRoutes() method based on the specified
     * PathToRoadyModuleDirectory.
     *
     * @return RouteCollection
     *
     */
    public function expectedRouteCollection(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesJSDirectory =
            $this->expectedPathToModulesJSDirectory(
                $pathToRoadyModuleDirectory
            );
        $routes = [];
        if($pathToModulesJSDirectory->__toString() !== sys_get_temp_dir()) {
            $jsFileNames = [];
            $directory = new RecursiveDirectoryIterator($pathToModulesJSDirectory->__toString());
            $iterator = new RecursiveIteratorIterator($directory);
            $jsFilePaths = new RegexIterator($iterator, '/^.+\.js$/i', RecursiveRegexIterator::GET_MATCH);
            foreach($jsFilePaths as $jsFilePath) {
                if(
                    is_array($jsFilePath)
                    &&
                    isset($jsFilePath[0])
                    &&
                    is_string($jsFilePath[0])
                    &&
                    file_exists($jsFilePath[0])
                ) {
                    // PATH TO JS FILE
                    $pathToJSFile = $jsFilePath[0];

                    // REQUEST NAME
                    $jsFileName = str_replace('.js', '', basename($pathToJSFile));
                    $jsFileNameParts = explode('_', $jsFileName);
                    $requestName = new NameInstance(
                        new Text(
                            $jsFileNameParts[array_key_first($jsFileNameParts)]
                            ??
                            $jsFileName
                        )
                    );

                    // POSITION
                    $position = new Position(
                        floatval(
                            strval($jsFileNameParts[array_key_last($jsFileNameParts)] ?? 0)
                        )
                    );

                    // RELATIVE PATH
                    $relativePathToJSFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $pathToJSFile);
                    $relativePathToJSFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToJSFile);
                    $safeTextForRelativePathToJSFile = [];
                    foreach($relativePathToJSFileParts as $relativePathPart) {
                        if(!empty($relativePathPart)) {
                            $safeTextForRelativePathToJSFile[] = new SafeText(new Text($relativePathPart));
                        }
                    }
                    $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToJSFile));
                    $routes[] = $this->newRouteToModuleJSFile(
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
     * Test determineJSRoutes returns the expected RouteCollection.
     *
     * @return void
     *
     * @covers ModuleJSDeterminator->determineJSRoutes()
     *
     */
    public function test_determineJSRoutes_returns_the_expected_RouteCollection(): void
    {
        $pathToRoadyModuleDirectory = $this->pathToRoadyTestModuleDirectory();
        $this->assertEquals(
            $this->expectedRouteCollection($pathToRoadyModuleDirectory),
            $this->moduleJSRouteDeterminatorTestInstance()->determineJSRoutes($pathToRoadyModuleDirectory),
            message: $this->testFailedMessage(
                $this->moduleJSRouteDeterminatorTestInstance(),
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

