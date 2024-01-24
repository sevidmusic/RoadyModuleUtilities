<?php

namespace Darling\RoadyModuleUtilities\classes\configuration;

use \Darling\PHPWebPaths\interfaces\paths\parts\url\Authority;
use \Darling\PHPTextTypes\classes\collections\NameCollection as NameCollectionInstance;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText as SafeTextInstance;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\collections\NameCollection;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader as ModuleRoutesJsonConfigurationReaderInterface;
use \Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator;
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

    /**
     * @var string $moduleName The index that is expected to be used
     *                         for the value configured for a Route's
     *                         module Name in a module's
     *                         routes.json configuration file.
     */
    private string $moduleNameIndex = 'module-name';

    /**
     * @var string $namedPositionsIndex The index that is expected to
     *                                  be used for the value
     *                                  configured for a Route's
     *                                  NamedPositions in a module's
     *                                  routes.json configuration
     *                                  file.
     */
    private string $namedPositionsIndex = 'named-positions';

    /**
     * @var string $positionIndex The index that is expected to be
     *                            used for the value configured for
     *                            a Position in a module's
     *                            routes.json configuration file.
     */
    private string $positionIndex = 'position';

    /**
     * @var string $positionNameIndex The index that is expected to
     *                                be used for the value configured
     *                                for a PositionName in a module's
     *                                routes.json configuration file.
     */
    private string $positionNameIndex = 'position-name';

    /**
     * @var string $relativePathIndex The index that is expected
     *                                to be used for the value
     *                                configured for a Route's
     *                                RelativePath in a module's
     *                                routes.json configuration file.
     */
    private string $relativePathIndex = 'relative-path';

    /**
     * @var string $respondsToIndex The index that is expected to be
     *                              used for the value configured for
     *                              the Names of the Requests that a
     *                              Route responds to in a module's
     *                              routes.json configuration file.
     */
    private string $respondsToIndex = 'responds-to';

    /**
     * @var string $emptyString An empty string.
     */
    private string $emptyString = '';

    public function determineConfiguredRoutes(
        Authority $authority,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        RoadyModuleFileSystemPathDeterminator $roadyModuleFileSystemPathDeterminator
    ): RouteCollection
    {
        $routes = [];
        $pathToRoutesJsonConfigurationFile =
        $roadyModuleFileSystemPathDeterminator->determinePathToFileInModuleDirectory(
            $pathToRoadyModuleDirectory,
            $this->stringToRelativePath(
                $this->expectedRoutesJsonConfigurationFileName($authority)
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
                                        $array[$this->respondsToIndex],
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
    private function expectedRoutesJsonConfigurationFileName(Authority $authority): Name
    {
        return new NameInstance(new Text(str_replace(':', '.', $authority->__toString()) . '.json'));
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
            (
                is_float($namedPosition[$this->positionIndex])
                ||
                is_int($namedPosition[$this->positionIndex])
            );
    }

    /**
     * Convert an array of arrays of string float pairs to a NamedPositionCollection.
     *
     * @param array<array<string|float>> $array The array to convert.
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

    /**
     * Return a Name for a module based on the specified
     * PathToRoadyModuleDirectory and $moduleName.
     *
     * If the $moduleName is not an empty string then it
     * will be used to construct the Name, otherwise the
     * PathToRoadyModuleDirectory's Name will be returned.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                  The PathToRoadyModuleDirectory
     *                                  instance that defines the
     *                                  path to the module.
     *
     * @param string $moduleName A string to use to consturct the
     *                           module's Name.
     *
     *                           Note: If the $moduleName is an empty
     *                           string then the Name assigned to the
     *                           specified PathToRoadyModuleDirectory
     *                           instance will be returned.
     *
     * @return Name
     *
     */
    private function determineModuleName(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        string $moduleName
    ): Name
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

    /**
     * Convert a string into a RelativePath.
     *
     * @param string $string The string to convert into a RelativePath.
     *
     * @return RelativePath
     *
     */
    private function stringToRelativePath(string $string): RelativePath
    {
        $stringParts = explode(
            DIRECTORY_SEPARATOR,
            $string
        );
        $relativePathSafeText = [];
        foreach($stringParts as $stringPart) {
            $relativePathSafeText[] =
                new SafeTextInstance(
                    new Text($stringPart)
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
     * @param array<int, string> $array An array of strings to
     *                                  convert into a NameCollection.
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

