<?php

namespace Darling\RoadyModuleUtilities\classes\determinators;

use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory as PathToExistingDirectoryInstance;
use \Darling\PHPFileSystemPaths\interfaces\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\classes\collections\NameCollection;
use \Darling\PHPTextTypes\classes\collections\SafeTextCollection as SafeTextCollectionInstance;
use \Darling\PHPTextTypes\classes\strings\Name as NameInstance;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\determinators\ModuleOutputRouteDeterminator as ModuleOutputRouteDeterminatorInterface;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\classes\collections\NamedPositionCollection;
use \Darling\RoadyRoutes\classes\collections\RouteCollection as RouteCollectionInstance;
use \Darling\RoadyRoutes\classes\identifiers\NamedPosition;
use \Darling\RoadyRoutes\classes\identifiers\PositionName;
use \Darling\RoadyRoutes\classes\paths\RelativePath;
use \Darling\RoadyRoutes\classes\routes\Route;
use \Darling\RoadyRoutes\classes\settings\Position;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \RecursiveDirectoryIterator;
use \RecursiveIteratorIterator;
use \RecursiveRegexIterator;
use \RegexIterator;

class ModuleOutputRouteDeterminator implements ModuleOutputRouteDeterminatorInterface
{

    public function determineOutputRoutes(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection
    {
        $pathToModulesOutputDirectory =
            $this->determinePathToModulesOutputDirectory(
                $pathToRoadyModuleDirectory
            );
        $routes = [];
        if($pathToModulesOutputDirectory->__toString() !== sys_get_temp_dir()) {
            $recursiveDirectoryIterator = new RecursiveDirectoryIterator(
                $pathToModulesOutputDirectory->__toString()
            );
            $recursiveDirectoryIteratorIterator = new RecursiveIteratorIterator(
                $recursiveDirectoryIterator
            );
            $outputFilePaths = new RegexIterator(
                $recursiveDirectoryIteratorIterator,
                '/^.+\.(php|html)$/i', RecursiveRegexIterator::GET_MATCH
            );
            foreach($outputFilePaths as $outputFilePath) {
                if(
                    is_array($outputFilePath)
                    &&
                    isset($outputFilePath[0])
                    &&
                    is_string($outputFilePath[0])
                    &&
                    file_exists($outputFilePath[0])
                ) {
                    $pathToOutputFile = $outputFilePath[0];
                    $outputFileName = str_replace(['.php', '.html'], '', basename($pathToOutputFile));
                    $outputFileNameParts = explode('_', $outputFileName);
                    $requestName = $this->determineRequestNameFromFileNameParts(
                        $outputFileNameParts,
                        $outputFileName,
                    );
                    $position = $this->determinePositionFromFileNameParts(
                        $outputFileNameParts
                    );
                    array_shift($outputFileNameParts);
                    array_pop($outputFileNameParts);
                    $positionNameString = implode('', $outputFileNameParts);
                    $positionName = new PositionName(new NameInstance(new Text((empty($positionNameString) ? 'roady-ui-named-position-c' : $positionNameString))));

                    $relativePathForRoute = $this->determineRelativePath(
                        $pathToRoadyModuleDirectory,
                        $pathToOutputFile
                    );
                    $routes[] = $this->newRouteToModuleOutputFile(
                        $pathToRoadyModuleDirectory->name(),
                        $requestName,
                        $positionName,
                        $position,
                        $relativePathForRoute,
                    );
                }
            }
        }
        return new RouteCollectionInstance(...$routes);
    }

    /**
     * Return a new Route to a Output file using the specified
     *
     * $moduleName, $requestName, $position, and $relativePath.
     *
     * @param Name $moduleName The Name of the module the Output file
     *                         belongs to.
     *
     * @param Name $requestName The Name of the only Request that
     *                          the Route will be mapped to.
     *
     * @param Position $position The Position to assign to the Route.
     *
     * @param RelativePath $relativePath The RelativePath to the
     *                                   Output file in the module's
     *                                   directory.
     *
     *
     * @return Route
     *
     */
    private function newRouteToModuleOutputFile(
        Name $moduleName,
        Name $requestName,
        PositionName $positionName,
        Position $position,
        RelativePath $relativePath
    ): Route
    {
        return new Route(
           $moduleName,
            new NameCollection($requestName),
            new NamedPositionCollection(
                new NamedPosition(
                    $positionName,
                    $position,
                ),
            ),
            $relativePath,
        );
    }

    /**
     * Return a PathToExistingDirectory instance for the path
     * to the output directory in the directory indicated by the
     * specified $pathToRoadyModuleDirectory.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
     *                                   The path to the roady module
     *                                   directory where the output
     *                                   directory is expected to be
     *                                   located.
     *
     * @return PathToExistingDirectory
     *
     */
    private function determinePathToModulesOutputDirectory(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): PathToExistingDirectory
    {
        $parts = $pathToRoadyModuleDirectory->pathToExistingDirectory()
                                            ->safeTextCollection()
                                            ->collection();
        $parts[] = new SafeText(new Text('output'));
        return new PathToExistingDirectoryInstance(
            new SafeTextCollectionInstance(...$parts)
        );
    }

    /**
     * Derive a RelativePath to a file in a Roady module directory
     * based on the specified $pathToFile.
     *
     * @param PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
     *                                   The path to the modules's
     *                                   root directory. This path
     *                                   will be stripped from
     *                                   the specified $pathToFile.
     *
     * @param string $pathToFile The complete path the file in the
     *                           module's directory.
     *
     * @return RelativePath
     *
     */
    private function determineRelativePath(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory,
        string $pathToFile
    ): RelativePath
    {
        $relativePathToFile = str_replace($pathToRoadyModuleDirectory->__toString(), '', $pathToFile);
        $relativePathToFileParts = explode(DIRECTORY_SEPARATOR, $relativePathToFile);
        $safeTextForRelativePathToFile = [];
        foreach($relativePathToFileParts as $relativePathPart) {
            if(!empty($relativePathPart)) {
                $safeTextForRelativePathToFile[] = new SafeText(new Text($relativePathPart));
            }
        }
        $relativePathForRoute = new RelativePath(new SafeTextCollectionInstance(...$safeTextForRelativePathToFile));
        return $relativePathForRoute;
    }

    /**
     * Determine an appropriate Position based on the specified array
     * of $fileNameParts.
     *
     * @param array<int, string> $fileNameParts
     *
     * @return Position
     *
     */
    private function determinePositionFromFileNameParts(
        array $fileNameParts
    ): Position
    {
        return new Position(
            floatval(
                strval(
                    $fileNameParts[array_key_last($fileNameParts)]
                    ??
                    0
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
                $defaultName
            )
        );
    }

}

