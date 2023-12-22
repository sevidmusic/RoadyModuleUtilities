<?php

namespace Darling\RoadyModuleUtilities\classes\configuration;

use \Darling\PHPTextTypes\classes\collections\NameCollection as NameCollectionInstance;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\collections\NameCollection;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator as RoadyModuleFileSystemPathDeterminatorInstance;
use \Darling\RoadyModuleUtilities\classes\paths\PathToRoadyModuleDirectory as PathToRoadyModuleDirectoryInstance;
use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader as ModuleRoutesJsonConfigurationReaderInterface;
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

class ModuleRoutesJsonConfigurationReader implements ModuleRoutesJsonConfigurationReaderInterface
{

    private string $moduleName = 'module-name';

    private string $namedPositions = 'named-positions';

    private string $position = 'position';

    private string $positionName = 'position-name';

    private string $relativePath = 'relative-path';

    private string $respondsTo = 'responds-to';

    private string $emptyString = '';

    /**
     * Determine the Routes that are expected to be defined based
     * on the provided json string.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *
     * @return RouteCollection
     *
     */
    public function determineConfiguredRoutes(
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
                                    $array[$this->moduleName]
                                    ??
                                    $this->emptyString
                                )
                            );
                            $nameCollection =
                                $this->arrayToNameCollection(
                                    array_filter(
                                        (
                                            $array[$this->respondsTo]
                                            ??
                                            []
                                        ),
                                        'is_string'
                                    )
                                );
                            $namedPositionCollection =
                                $this->arrayToNamedPositionCollection(
                                    array_filter(
                                        $array[$this->namedPositions],
                                        'is_array'
                                    )
                                );
                            $relativePath = $this->stringToRelativePath(
                                $array[$this->relativePath]
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
            isset($array[$this->respondsTo])
            &&
            is_array($array[$this->respondsTo])
            &&
            isset($array[$this->namedPositions])
            &&
            is_array($array[$this->namedPositions])
            &&
            isset($array[$this->relativePath])
            &&
            is_string($array[$this->relativePath]);
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
        return isset($namedPosition[$this->positionName])
            &&
            is_string($namedPosition[$this->positionName])
            &&
            isset($namedPosition[$this->position])
            &&
            (is_float($namedPosition[$this->position]) || is_int($namedPosition[$this->position]));
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
                                    $namedPositionArray[$this->positionName]
                                    ??
                                    $this->emptyString
                                )
                            )
                        )
                    ),
                    new PositionInstance(
                        floatval(
                            $namedPositionArray[$this->position]
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
}

