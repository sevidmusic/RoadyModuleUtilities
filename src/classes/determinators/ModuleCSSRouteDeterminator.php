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
    private const CSS_ROUTE_POSITION_NAME = 'roady-css-stylesheet-links';

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

                    $position = $this->determinePositionFromFileNameParts($cssFileNameParts);
                    $relativePathForRoute = $this->determineRelativePath($pathToRoadyModuleDirectory, $pathToCssFile);
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
     * Return the PositionName that should be used for all
     * CSS Routes.
     *
     * The PositionName will always be "roady-css-stylesheet-links".
     *
     * This position name will correspond to the name of the
     * position placeholder in the template file used to view
     * each css Routes output.
     *
     * ```
     * <!-- Place holder will be: -->
     * <roady-css-stylesheet-links></roady-css-stylesheet-links>
     *
     * ```
     *
     * @return PositionName
     *
     */
    private function positionNameForCSSRoutes(): PositionName
    {
        return new PositionName(
            new NameInstance(new Text(self::CSS_ROUTE_POSITION_NAME))
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

    /**
     * Return a PathToExistingDirectory instance for the path
     * to the css directory in the directory indicated by the
     * specified $pathToRoadyModuleDirectory.
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

    private function determineRelativePath(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory, string $pathToCssFile): RelativePath
    {
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
                    '.css',
                    '',
                    strval($fileNameParts[array_key_last($fileNameParts)] ?? 0)
                )
            )
        );
    }
}

