<?php

namespace Darling\RoadyModuleUtilities\classes\paths;

use Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use \Darling\PHPFileSystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory as PathToRoadyModuleDirectoryInterface;

class PathToRoadyModuleDirectory implements PathToRoadyModuleDirectoryInterface
{

    /**
     * Instantiate a new PathToRoadyModuleDirectory instance using
     * the specified PathToDirectoryOfRoadyModules and Name.
     *
     * Note: If the PathToDirectoryOfRoadyModules and Name can not
     * be used to define a path to an existing directory then the
     * path defined by this PathToRoadyModuleDirectory instance will
     * be the path to the systems temporary directory.
     *
     * @param PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules
     *                                   An instance of a
     *                                   PathToDirectoryOfRoadyModules
     *                                   that defines the path to the
     *                                   directory where the module's
     *                                   root directory is expected to
     *                                   be located.
     *
     * @param Name $name The name of the module.
     *
     */
    public function __construct(
        private PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules,
        private Name $name,
    ) { }

    public function name(): Name
    {
        return $this->name;
    }

    public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules
    {
        return $this->pathToDirectoryOfRoadyModules;
    }

    public function pathToExistingDirectory(): PathToExistingDirectory
    {
        $pathParts = $this->pathToDirectoryOfRoadyModules()
                          ->pathToExistingDirectory()
                          ->safeTextCollection()
                          ->collection();
        $pathParts[] = new SafeText($this->name());
        $pathToExistingDirectory = new PathToExistingDirectory(
            new SafeTextCollection(
                ...$pathParts
            ),
        );
        return $pathToExistingDirectory;
    }

    public function __toString(): string
    {
        return $this->pathToExistingDirectory()->__toString();
    }

}

