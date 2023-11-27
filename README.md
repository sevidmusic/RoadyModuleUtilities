

# RoadyModuleUtilities

The RoadyModuleUtilities library will provide classes for working with
Roady modules.


### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationReader

A ModuleAuthoritiesJsonConfigurationReader can return an AuthorityCollection
constructed from the authorities defined in a specified Module's
`authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface ModuleAuthoritiesJsonConfigurationReader
{
    public function read(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): AuthorityCollection;
}

```
Example `authorities.json`:

```
[
    'localhost:8080',
    'www.example.com',
    'subDomain.domain.topLevelDomain:8080',
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationWriter

A ModuleAuthoritiesJsonConfigurationWriter can write a specified AuthorityCollection
to a specified Module's `authorities.json` configuration file.

Warning: The write() method will overwrite an existing
`authorities.json` file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface ModuleAuthoritiesJsonConfigurationWriter
{
    public function write(
        AuthorityCollection $authorityCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;
}


```
Example `authorities.json`:

```
[
    'localhost:8080',
    'www.example.com',
    'subDomain.domain.topLevelDomain:8080',
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationEditor

A ModuleAuthoritiesJsonConfigurationEditor can add or remove Authorities
from a specified Modules `authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\paths\parts\url\Authority;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface ModuleAuthoritiesJsonConfigurationEditor
{

    public function add(
        Authority $authority,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function addMultiple(
        AuthorityCollection $authorityCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function remove(
        Authority $authority,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function removeMultiple(
        AuthorityCollection $authorityCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

}


```
Example `authorities.json`:

```
[
    'localhost:8080',
    'www.example.com',
    'subDomain.domain.topLevelDomain:8080',
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationReader

A ModuleRoutesJsonConfigurationReader can return an RouteCollection
constructed from the Routes defined in a specified Module's
`routes.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

interface ModuleRoutesJsonConfigurationReader
{

    public function read(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

```

Example `routes.json`:

```
[
    [
        'module-name' => 'ModuleName',
        'route-names' => [
            'hello-world',
        ],
        'route-named-positions' => [
            ['hello-world', 0.001],
        ],
        'relative-path' => 'relative/path/to/output/file/HelloWorld.php'
    ],
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationWriter

A ModuleRoutesJsonConfigurationWriter can write a specified RouteCollection
to a specified Module's `routes.json` configuration file.

Warning: The write() method will overwrite an existing
`routes.json` file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\PHPWebPaths\interfaces\collections\RouteCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

interface ModuleRoutesJsonConfigurationWriter
{
    public function write(
        RouteCollection $routeCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;
}


```
Example `routes.json`:

```
[
    [
        'module-name' => 'ModuleName',
        'route-names' => [
            'hello-world',
        ],
        'route-named-positions' => [
            ['hello-world', 0.001],
        ],
        'relative-path' => 'relative/path/to/output/file/HelloWorld.php'
    ],
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationEditor

A ModuleRoutesJsonConfigurationEditor can add or remove Routes
from a specified Modules `routes.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\PHPWebPaths\interfaces\collections\RouteCollection;
use \Darling\PHPWebPaths\interfaces\paths\parts\url\Route;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

interface ModuleRoutesJsonConfigurationEditor
{

    public function add(
        Route $route,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function addMultiple(
        RouteCollection $routeCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function remove(
        Route $route,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

    public function removeMultiple(
        RouteCollection $routeCollection,
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): bool;

}


```
Example `routes.json`:

```
[
    [
        'module-name' => 'ModuleName',
        'route-names' => [
            'hello-world',
        ],
        'route-named-positions' => [
            ['hello-world', 0.001],
        ],
        'relative-path' => 'relative/path/to/output/file/HelloWorld.php'
    ],
]

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory

A PathToRoadyModuleDirectory defines a path to an existing
Roady Module's directory.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Stringable;

interface PathToRoadyModuleDirectory extends Stringable
{

   public function moduleName(): Name;
   public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules;
   public function __toString(): string;

}

```

### \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection

A PathToRoadyModuleDirectoryCollection defines a collection of
PathToRoadyModuleDirectory instances.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\collections;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

interface PathToRoadyModuleDirectoryCollection
{

   /**
    * Return an array of PathToRoadyModuleDirectory instances.
    *
    * @return array<int, PathToRoadyModuleDirectory>
    *                                       An array of
    *                                       PathToRoadyModuleDirectory
    *                                       instances.
    */
   public function collection(): array;

}

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules

A PathToDirectoryOfRoadyModules defines a path to an existing directory
where Roady Modules are expected to be located.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPFilesystemPaths\interfaces\paths\PathToExistingDirectory;
use \Stringable;

interface PathToDirectoryOfRoadyModules extends Stringable
{

   public function pathToExistingDirectory(): PathToExistingDirectory;

   public function __toString(): string;

}

```

### \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;

A ListingOfDirectoryOfRoadyModules defines a
PathToRoadyModuleDirectoryCollection of PathToRoadyModuleDirectory
instances for the module directories in the assigned
PathToDirectoryOfRoadyModules.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\directory\listings;

use \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;

interface ListingOfDirectoryOfRoadyModules
{

   public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules;
   public function pathToRoadyModuleDirectoryCollection(): PathToRoadyModuleDirectoryCollection;

}


```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleCSSRouteDeterminator;

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutingUtilties\interfaces\collections\RouteCollection;

interface ModuleCSSRouteDeterminator
{

    public function pathToRoadyModuleDirectory(): PathToRoadyModuleDirectory;

    public function routeCollection(): RouteCollection

}

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleJSRouteDeterminator;

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutingUtilties\interfaces\collections\RouteCollection;

interface ModuleJSRouteDeterminator
{

    public function pathToRoadyModuleDirectory(): PathToRoadyModuleDirectory;

    public function routeCollection(): RouteCollection

}

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleOutputRouteDeterminator;

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutingUtilties\interfaces\collections\RouteCollection;

interface ModuleOutputRouteDeterminator
{

    public function pathToRoadyModuleDirectory(): PathToRoadyModuleDirectory;

    public function routeCollection(): RouteCollection

}
```

