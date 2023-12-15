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
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleJSRouteDeterminator as ModuleJSRouteDeterminatorInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

class ModuleJSRouteDeterminator implements ModuleJSRouteDeterminatorInterface
{
    private const JS_ROUTE_POSITION_NAME = 'roady-js-script-tags';

    public function determineJSRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesJSDirectory =
            $this->determinePathToModulesJSDirectory(
                $pathToRoadyModuleDirectory
            );
        $routes = [];
        if($pathToModulesJSDirectory->__toString() !== sys_get_temp_dir()) {
            $recursiveDirectoryIterator = new RecursiveDirectoryIterator(
                $pathToModulesJSDirectory->__toString()
            );
            $recursiveDirectoryIteratorIterator = new RecursiveIteratorIterator(
                $recursiveDirectoryIterator
            );
            $jsFilePaths = new RegexIterator(
                $recursiveDirectoryIteratorIterator,
                '/^.+\.js$/i', RecursiveRegexIterator::GET_MATCH
            );
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
                    $pathToJSFile = $jsFilePath[0];
                    $jsFileName = basename($pathToJSFile);
                    $jsFileNameParts = explode('_', $jsFileName);
                    $requestName = $this->determineRequestNameFromFileNameParts(
                        $jsFileNameParts,
                        $jsFileName,
                    );
                    $position = $this->determinePositionFromFileNameParts(
                        $jsFileNameParts
                    );
                    $relativePathForRoute = $this->determineRelativePath(
                        $pathToRoadyModuleDirectory,
                        $pathToJSFile
                    );
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
     * Return the PositionName that should be used for all
     * JS Routes.
     *
     * The PositionName will always be "roady-js-script-tags".
     *
     * This position name will correspond to the name of the
     * position placeholder in the template file used to view
     * each js Routes output.
     *
     * ```
     * <!-- Place holder will be: -->
     * <roady-js-script-tags></roady-js-script-tags>
     *
     * ```
     *
     * @return PositionName
     *
     */
    private function positionNameForJSRoutes(): PositionName
    {
        return new PositionName(
            new NameInstance(new Text(self::JS_ROUTE_POSITION_NAME))
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
    private function newRouteToModuleJSFile(Name $moduleName, Name $requestName, Position $position, RelativePath $relativePath): Route
    {
        return new Route(
           $moduleName,
            new NameCollection($requestName),
            new NamedPositionCollection(
                new NamedPosition(
                    $this->positionNameForJSRoutes(),
                    $position,
                ),
            ),
            $relativePath,
        );
    }

    /**
     * Return a PathToExistingDirectory instance for the path
     * to the js directory in the directory indicated by the
     * specified $pathToRoadyModuleDirectory.
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
    private function determinePathToModulesJSDirectory(
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

    private function determineRelativePath(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory, string $pathToJSFile): RelativePath
    {
        $relativePathToJSFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $pathToJSFile);
        $relativePathToJSFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToJSFile);
        $safeTextForRelativePathToJSFile = [];
        foreach($relativePathToJSFileParts as $relativePathPart) {
            if(!empty($relativePathPart)) {
                $safeTextForRelativePathToJSFile[] = new SafeText(new Text($relativePathPart));
            }
        }
        $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToJSFile));
        return $relativePathForRoute;
    }

    /**
     * Determine an approrpiate Position based on the specified array
     * of $fileNameParts.
     *
     * @param array<int, string> $fileNameParts
     *
     * @return Position
     *
     */
    private function determinePositionFromFileNameParts(array $fileNameParts): Position
    {
        return new Position(
            floatval(
                str_replace(
                    '.js',
                    '',
                    strval($fileNameParts[array_key_last($fileNameParts)] ?? 0)
                )
            )
        );
    }

    /**
     * Determine an appropriate Request Name based on the specified
     * $fileNameParts. If an appropriate Request Name cannot be
     * determined default to a Name constructed from the specified
     * $defaultName.
     *
     * @param array<int, string> $fileNameParts
     *
     * @return Name
     *
     */
    private function determineRequestNameFromFileNameParts(
        array $fileNameParts,
        string $defaultName,
    ): Name
    {
        return new NameInstance(
            new Text(
                $fileNameParts[array_key_first($fileNameParts)]
                ??
                str_replace('.js', '', $defaultName)
            )
        );
    }

}

