<?php

namespace Darling\RoadyModuleUtilities\tests\classes\determinators;

use \Darling\RoadyModuleUtilities\classes\determinators\RoadyModuleFileSystemPathDeterminator;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\determinators\RoadyModuleFileSystemPathDeterminatorTestTrait;

class RoadyModuleFileSystemPathDeterminatorTest extends RoadyModuleUtilitiesTest
{

    /**
     * The RoadyModuleFileSystemPathDeterminatorTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\determinators\RoadyModuleFileSystemPathDeterminator
     * interface.
     *
     * @see RoadyModuleFileSystemPathDeterminatorTestTrait
     *
     */
    use RoadyModuleFileSystemPathDeterminatorTestTrait;

    public function setUp(): void
    {
        $this->setRoadyModuleFileSystemPathDeterminatorTestInstance(
            new RoadyModuleFileSystemPathDeterminator()
        );
    }
}

