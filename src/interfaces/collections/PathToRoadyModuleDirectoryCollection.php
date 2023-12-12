<?php

namespace Darling\RoadyModuleUtilities\interfaces\collections;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

/**
 * Defines a collection of PathToRoadyModuleDirectory instances.
 *
 * @see PathToRoadyModuleDirectory
 *
 */
interface PathToRoadyModuleDirectoryCollection
{

    /**
     * Return the collection of PathToRoadyModuleDirectory instances.
     *
     * @return array<int, PathToRoadyModuleDirectory>
     *
     */
    public function collection(): array;

}

