<?php

namespace Darling\RoadyModuleUtilities\tests\classes\configuration;

use \Darling\RoadyModuleUtilities\classes\configuration\ModuleRoutesJsonConfigurationReader;
use \Darling\RoadyModuleUtilities\tests\RoadyModuleUtilitiesTest;
use \Darling\RoadyModuleUtilities\tests\interfaces\configuration\ModuleRoutesJsonConfigurationReaderTestTrait;

class ModuleRoutesJsonConfigurationReaderTest extends RoadyModuleUtilitiesTest
{

    /**
     * The ModuleRoutesJsonConfigurationReaderTestTrait defines
     * common tests for implementations of the
     * Darling\RoadyModuleUtilities\interfaces\configuration\ModuleRoutesJsonConfigurationReader
     * interface.
     *
     * @see ModuleRoutesJsonConfigurationReaderTestTrait
     *
     */
    use ModuleRoutesJsonConfigurationReaderTestTrait;

    public function setUp(): void
    {
        $this->setModuleRoutesJsonConfigurationReaderTestInstance(
            new ModuleRoutesJsonConfigurationReader()
        );
    }
}

