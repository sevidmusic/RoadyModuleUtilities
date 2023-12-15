<?php

namespace Darling\RoadyModuleUtilities\tests\classes\determinators;

use \Darling\RoadyModuleUtilities\classes\determinators\ModuleOutputRouteDeterminator;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\determinators\ModuleOutputRouteDeterminatorTestTrait;

class ModuleOutputRouteDeterminatorTest extends RoadyModuleUtilitiesTest
{

    /**
     * The ModuleOutputRouteDeterminatorTestTrait defines common tests
     * for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\determinators\ModuleOutputRouteDeterminator
     * interface.
     *
     * @see ModuleOutputRouteDeterminatorTestTrait
     *
     */
    use ModuleOutputRouteDeterminatorTestTrait;

    public function setUp(): void
    {
        $this->setModuleOutputRouteDeterminatorTestInstance(
            new ModuleOutputRouteDeterminator()
        );
    }
}

