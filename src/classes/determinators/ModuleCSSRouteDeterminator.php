<?php

namespace Darling\RoadyModuleUtilities\classes\determinators;

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
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator as ModuleCSSRouteDeterminatorInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

class ModuleCSSRouteDeterminator implements ModuleCSSRouteDeterminatorInterface
{

    public function determineCSSRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesCSSDirectory =
            $this->determinePathToModulesCSSDirectory(
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

    private function positionNameForCSSRoutes(): PositionName
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
                    $this->positionNameForCSSRoutes(),
                    $position,
                ),
            ),
            $relativePath,
        );
    }

    private function determinePathToModulesCSSDirectory(
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

}

