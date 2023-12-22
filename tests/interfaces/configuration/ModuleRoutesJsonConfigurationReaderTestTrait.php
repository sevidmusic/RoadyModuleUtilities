<?php

namespace Darling\RoadyModuleUtilities\tests\interfaces\configuration;

use \Darling\PHPTextTypes\classes\collections\NameCollection as NameCollectionInstance;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\collections\NameCollection;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator as RoadyModuleFileSystemPathDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory as PathToRoadyModuleDirectoryInstance;
use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\NamedPositionCollection as NamedPositionCollectionInstance;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\classes\identifiers\NamedPosition as NamedPositionInstance;
use \Darling\RoadyRoutes\classes\identifiers\PositionName as PositionNameInstance;
use \Darling\RoadyRoutes\classes\paths\RelativePath as RelativePathInstance;
use \Darling\RoadyRoutes\classes\routes\Route as RouteInstance;
use \Darling\RoadyRoutes\classes\settings\Position as PositionInstance;
use \Darling\RoadyRoutes\interfaces\collections\NamedPositionCollection;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyRoutes\interfaces\paths\RelativePath;

/**
 * The ModuleRoutesJsonConfigurationReaderTestTrait defines common
 * tests for implementations of the ModuleRoutesJsonConfigurationReader
 * interface.
 *
 * @see ModuleRoutesJsonConfigurationReader
 *
 */
trait ModuleRoutesJsonConfigurationReaderTestTrait
{

    private string $moduleNameIndex = 'module-name';

    private string $namedPositionsIndex = 'named-positions';

    private string $positionIndex = 'position';

    private string $positionNameIndex = 'position-name';

    private string $relativePathIndex = 'relative-path';

    private string $respondsToIndex = 'responds-to';

    private string $emptyString = '';

    /**
     * @var ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReader
     *                            An instance of a
     *                            ModuleRoutesJsonConfigurationReader
     *                            implementation to test.
     */
    protected ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReader;

    /**
     * Set up an instance of a
     * ModuleRoutesJsonConfigurationReader implementation to test.
     *
     * This method must also set the ModuleRoutesJsonConfigurationReader
     * implementation instance to be tested via the
     * setModuleRoutesJsonConfigurationReaderTestInstance() method.
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
     *     $this->setModuleRoutesJsonConfigurationReaderTestInstance(
     *         new ModuleRoutesJsonConfigurationReader()
     *     );
     * }
     *
     * ```
     *
     */
    abstract protected function setUp(): void;

    /**
     * Return the ModuleRoutesJsonConfigurationReader
     * implementation instance to test.
     *
     * @return ModuleRoutesJsonConfigurationReader
     *
     */
    protected function moduleRoutesJsonConfigurationReaderTestInstance(): ModuleRoutesJsonConfigurationReader
    {
        return $this->moduleRoutesJsonConfigurationReader;
    }

    /**
     * Set the ModuleRoutesJsonConfigurationReader implementation
     * instance to test.
     *
     * @param ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReaderTestInstance
     *                              An instance of an
     *                              implementation of
     *                              the ModuleRoutesJsonConfigurationReader
     *                              interface to test.
     *
     * @return void
     *
     */
    protected function setModuleRoutesJsonConfigurationReaderTestInstance(
        ModuleRoutesJsonConfigurationReader $moduleRoutesJsonConfigurationReaderTestInstance
    ): void
    {
        $this->moduleRoutesJsonConfigurationReader = $moduleRoutesJsonConfigurationReaderTestInstance;
    }

    private function determinePathToRoadyModuleDirectory(Name $moduleNameInstance): PathToRoadyModuleDirectory
    {
        return new PathToRoadyModuleDirectoryInstance(
            $this->pathToDirectoryOfRoadyTestModules(),
            $moduleNameInstance,
        );
    }

    /**
     * Return the expected Name of a module's Routes json
     * configuration file.
     *
     * @return Name
     *
     */
    private function expectedRoutesJsonConfigurationFileName(): Name
    {
        return new NameInstance(new Text('routes.json'));
    }

    /**
     * Determine if the provided array defines the parameters
     * expected by a Route, return true if it does, false otherwise.
     *
     * @param array<mixed> $array The array to check.
     *
     * @return bool
     *
     */
    private function arrayDefinesARoute(array $array): bool
    {
        return
            // No need to check for module-name, if it is not specified the modules name will be the name of the module that defines the routes.json
            isset($array[$this->respondsToIndex])
            &&
            is_array($array[$this->respondsToIndex])
            &&
            isset($array[$this->namedPositionsIndex])
            &&
            is_array($array[$this->namedPositionsIndex])
            &&
            isset($array[$this->relativePathIndex])
            &&
            is_string($array[$this->relativePathIndex]);
    }

    /**
     * Determine if the provided array defines the parameters
     * expected by a NamedPosition, return true if it does,
     * false otherwise.
     *
     * @param array<mixed> $namedPosition The array to check.
     *
     * @return bool
     *
     */
    private function namedPositionArrayIsValid(array $namedPosition): bool
    {
        return isset($namedPosition[$this->positionNameIndex])
            &&
            is_string($namedPosition[$this->positionNameIndex])
            &&
            isset($namedPosition[$this->positionIndex])
            &&
            (is_float($namedPosition[$this->positionIndex]) || is_int($namedPosition[$this->positionIndex]));
    }

    /**
     * Convert an array of arrays of string float pairs to a NamedPositionCollection.
     *
     * @param array<array<string|float>> $array
     *
     * @return NamedPositionCollection
     *
     */
    private function arrayToNamedPositionCollection(array $array): NamedPositionCollection
    {
        $namedPositionInstances = [];
        foreach($array as $key => $namedPositionArray) {
            if( $this->namedPositionArrayIsValid($namedPositionArray)) {
                $namedPositionInstances[] = new NamedPositionInstance(
                    new PositionNameInstance(
                        new NameInstance(
                            new Text(
                                strval(
                                    $namedPositionArray[$this->positionNameIndex]
                                    ??
                                    $this->emptyString
                                )
                            )
                        )
                    ),
                    new PositionInstance(
                        floatval(
                            $namedPositionArray[$this->positionIndex]
                            ??
                            0
                        )
                    ),
                );
            }
        }
        return new NamedPositionCollectionInstance(
            ...$namedPositionInstances
        );
    }

    private function determineModuleName(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory, string $moduleName): Name
    {
        return match(
            !empty($moduleName)
        ) {
            true => new NameInstance(
                new Text($moduleName)
            ),
            default =>
                $pathToRoadyModuleDirectory->name(),
        };
    }

    private function stringToRelativePath(string $relativePath): RelativePath
    {
        $relativePathParts = explode(
            DIRECTORY_SEPARATOR,
            $relativePath
        );
        $relativePathSafeText = [];
        foreach($relativePathParts as $relativePathPart) {
            $relativePathSafeText[] =
                new SafeTextInstance(
                    new Text($relativePathPart)
                );
        }
        return new RelativePathInstance(
            new SafeTextCollectionInstance(
                ...$relativePathSafeText
            )
        );
    }

    /**
     * Determine the Routes that are expected to be defined based
     * on the provided json string.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *
     * @return RouteCollection
     *
     */
    private function determineExpectedRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
    ): RouteCollection
    {
        $routes = [];
        $pathToRoutesJsonConfigurationFile =
        $roadyModuleFileSystemPathDeterminator->determinePathToFileInModuleDirectory(
            $pathToRoadyModuleDirectory,
            $this->stringToRelativePath(
                $this->expectedRoutesJsonConfigurationFileName()
                     ->__toString()
            ),
        );
        if(file_exists($pathToRoutesJsonConfigurationFile)) {
            $json = strval(
                file_get_contents($pathToRoutesJsonConfigurationFile)
            );
            if(json_validate($json)) {
                $data = json_decode($json, associative: true);
                if(is_array($data)) {
                    $arrays = array_filter($data, 'is_array');
                    foreach($arrays as $array) {
                        if($this->arrayDefinesARoute($array)) {
                            $moduleName = $this->determineModuleName(
                                $pathToRoadyModuleDirectory,
                                (
                                    $array[$this->moduleNameIndex]
                                    ??
                                    $this->emptyString
                                )
                            );
                            $nameCollection =
                                $this->arrayToNameCollection(
                                    array_filter(
                                        (
                                            $array[$this->respondsToIndex]
                                            ??
                                            []
                                        ),
                                        'is_string'
                                    )
                                );
                            $namedPositionCollection =
                                $this->arrayToNamedPositionCollection(
                                    array_filter(
                                        $array[$this->namedPositionsIndex],
                                        'is_array'
                                    )
                                );
                            $relativePath = $this->stringToRelativePath(
                                $array[$this->relativePathIndex]
                                ??
                                $this->emptyString
                            );
                            if(
                                file_exists(
                                    $roadyModuleFileSystemPathDeterminator->determinePathToFileInModuleDirectory(
                                        $pathToRoadyModuleDirectory,
                                        $relativePath
                                    )
                                )
                            )
                            {
                                $routes[] = new RouteInstance(
                                    $moduleName,
                                    $nameCollection,
                                    $namedPositionCollection,
                                    $relativePath
                                );
                            }
                        }
                    }
                }
            }
        }
        return new RouteCollectionInstance(...$routes);
    }

    /**
     * Convert an array of strings to a NameCollection.
     *
     * @param array<int, string> $array
     *
     * @return NameCollection
     *
     */
    private function arrayToNameCollection(array $array): NameCollection
    {
        $names = [];
        foreach($array as $value) {
            if(is_string($value)) {
                $names[] = new NameInstance(new Text($value));
            }
        }
        return new NameCollectionInstance(...$names);
    }

    /**
     * Test determineConfiguredRoutes returns an empty
     * RouteCollection if the module does not define a json
     * configuration file
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_an_empty_RouteCollection_if_the_module_does_not_define_a_json_configuration_file(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('empty-module'))
        );
        $this->assertEmpty(
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    new RoadyModuleFileSystemPathDeterminatorInstance()
                )->collection(),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return an empty RouteCollection if the module ' .
                'does not define a json configuration file',
            ),
        );
    }

    /**
     * Test determineConfiguredRoutes returns an empty
     * RouteCollection if the module defines an empty routes json
     * configuration file
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_an_empty_RouteCollection_if_the_module_defines_empty_routes_json_configuration_file(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('module-defines-empty-routes-json-configuration-file'))
        );
        $this->assertEmpty(
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    new RoadyModuleFileSystemPathDeterminatorInstance()
                )->collection(),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return an empty RouteCollection if the module ' .
                'does defines an empty json configuration file',
            ),
        );
    }

    /**
     * Test determineConfiguredRoutes returns an empty
     * RouteCollection if the module defines an invalid routes json
     * configuration file
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_an_empty_RouteCollection_if_the_module_defines_an_invalid_routes_json_configuration_file(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('module-defines-invalid-routes-json-configuration-file'))
        );
        $this->assertEmpty(
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    new RoadyModuleFileSystemPathDeterminatorInstance()
                )->collection(),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return an empty RouteCollection if the module ' .
                'does defines an invalid json configuration file',
            ),
        );
    }

    /**
     * Test determineConfiguredRoutes returns an empty
     * RouteCollection if the module defines a valid routes json
     * configuration file that does not define any routes
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_an_empty_RouteCollection_if_the_module_defines_a_valid_routes_json_configuration_file_that_does_not_define_any_routes(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('module-defines-valid-routes-json-configuration-file-that-does-not-define-any-routes'))
        );
        $this->assertEmpty(
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    new RoadyModuleFileSystemPathDeterminatorInstance()
                )->collection(),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return an empty RouteCollection if the module ' .
                'does not defines any Routes in it\'s json ' .
                'configuration file',
            ),
        );
    }

    /**
     * Test determineConfiguredRoutes returns all of the defined
     * Routes if the module defines a valid routes json
     * configuration file that defines routes and other types of values
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_all_of_the_defined_Routes_if_the_module_defines_a_valid_routes_json_configuration_file_that_defines_routes_and_other_types_of_values(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('module-defines-valid-routes-json-configuration-file-that-defines-routes-and-other-types-of-values'))
        );
        $roadyModuleFileSystemPathDeterminator =
            new RoadyModuleFileSystemPathDeterminatorInstance();
        $expectedRoutes = $this->determineExpectedRoutes(
            $pathToRoadyModuleDirectory,
            $roadyModuleFileSystemPathDeterminator
        );
        $this->assertEquals(
            $expectedRoutes,
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    $roadyModuleFileSystemPathDeterminator
                ),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return all of the defined Routes if the module ' .
                'defines a valid routes json configuration file ' .
                'that defines routes and other types of values'
            ),
        );
    }

    /**
     * Test determineConfiguredRoutes returns all of the defined
     * Routes if the module defines a valid routes json
     * configuration file that only defines routes
     *
     * @return void
     *
     * @covers ModuleRoutesJsonConfigurationReader->determineConfiguredRoutes()
     *
     */
    public function test_determineConfiguredRoutes_returns_all_of_the_defined_Routes_if_the_module_defines_a_valid_routes_json_configuration_file_that_only_defines_routes(): void
    {
        $pathToRoadyModuleDirectory = $this->determinePathToRoadyModuleDirectory(
            new NameInstance(new Text('module-defines-valid-routes-json-configuration-file-that-only-defines-routes'))
        );
        $roadyModuleFileSystemPathDeterminator =
            new RoadyModuleFileSystemPathDeterminatorInstance();
        $expectedRoutes = $this->determineExpectedRoutes(
            $pathToRoadyModuleDirectory,
            $roadyModuleFileSystemPathDeterminator
        );
        $this->assertEquals(
            $expectedRoutes,
            $this->moduleRoutesJsonConfigurationReaderTestInstance()
                ->determineConfiguredRoutes(
                    $pathToRoadyModuleDirectory,
                    $roadyModuleFileSystemPathDeterminator
                ),
            $this->testFailedMessage(
                $this->moduleRoutesJsonConfigurationReaderTestInstance(),
                'collection',
                'return all of the defined Routes if the module ' .
                'defines a valid routes json configuration file ' .
                'that only defines routes'
            ),
        );
    }

    abstract protected function testFailedMessage(object $testedInstance, string $testedMethod, string $expectation): string;
    abstract public static function assertSame(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void;
    abstract public static function assertEmpty(mixed $actual, string $message = ''): void;
    abstract public function pathToDirectoryOfRoadyTestModules(): PathToDirectoryOfRoadyModules;

}

