<?php

namespace Darling\RoadyModuleUtilities\tests\classes\determinators;

use \Darling\RoadyModuleUtilities\classes\determinators\ModuleCSSRouteDeterminator;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\determinators\ModuleCSSRouteDeterminatorTestTrait;

class ModuleCSSRouteDeterminatorTest extends RoadyModuleUtilitiesTest
{

    /**
     * The ModuleCSSRouteDeterminatorTestTrait defines common tests
     * for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\determinators\ModuleCSSRouteDeterminator
     * interface.
     *
     * @see ModuleCSSRouteDeterminatorTestTrait
     *
     */
    use ModuleCSSRouteDeterminatorTestTrait;

    public function setUp(): void
    {
        $this->setModuleCSSRouteDeterminatorTestInstance(
            new ModuleCSSRouteDeterminator()
        );
    }
}

