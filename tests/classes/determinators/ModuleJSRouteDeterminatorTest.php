<?php

namespace Darling\RoadyModuleUtilities\tests\classes\determinators;

use \Darling\RoadyModuleUtilities\classes\determinators\ModuleJSRouteDeterminator;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\determinators\ModuleJSRouteDeterminatorTestTrait;

class ModuleJSRouteDeterminatorTest extends RoadyModuleUtilitiesTest
{

    /**
     * The ModuleJSRouteDeterminatorTestTrait defines common tests
     * for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\determinators\ModuleJSRouteDeterminator
     * interface.
     *
     * @see ModuleJSRouteDeterminatorTestTrait
     *
     */
    use ModuleJSRouteDeterminatorTestTrait;

    public function setUp(): void
    {
        $this->setModuleJSRouteDeterminatorTestInstance(
            new ModuleJSRouteDeterminator()
        );
    }
}

