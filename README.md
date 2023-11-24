# RoadyModuleUtilities

Provides classes for working with Roady modules.

TODO:

Class responsible for dynamically creating Routes based on Module
files in Module's `output/`, `css/`, and `js/` directories.

# Draft/Design Notes

Sudo code for how this library will be used by Roady in conjunction with the RoadyRoutingUtilities library:

```
<?php

/**
 * index.php
 */

 * ^ Note:
 *
 * // A Request instantiated without any parameters will be based on
 * the current request:
 *
 * For example
 *
 * $url = (
 *     isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'
 *     ? 'https'
 *     : 'http'
 * ) .
 * '://' .
 * ($_SERVER['HTTP_HOST'] ?? '') .
 * ($_SERVER['REQUEST_URI'] ?? '');
 *
 */
$ui = new RoadyUI(
    new PathToDirectoryOfRoadyTemplates(
        new PathToExisitingDirectory(
            new SafeTextCollection(
                new SafeText('path'),
                new SafeText('to'),
                new SafeText('roady'),
                new SafeText('templates'),
                new SafeText('directory')
            )
        )
    ),
    new Router(
        /** @see comment ^ */
        new Request(),
        new PathToDirectoryOfRoadyModules(
            new PathToExisitingDirectory(
                new SafeTextCollection(
                    new SafeText('path'),
                    new SafeText('to'),
                    new SafeText('roady'),
                    new SafeText('modules'),
                    new SafeText('directory')
                )
            )
        ),
    ),
);

```

## Pseudo Router Definition

```
class Router
{
    private AuthoritiesJsonConfigurationReader $authoritiesJsonConfigurationReader;
    private RoutesJsonConfigurationReader $routesJsonConfigurationReader;
    private ModuleOutputRouteDeterminator $moduleOutputRouteDeterminator;
    private ModuleCSSRouteDeterminator $moduleCSSRouteDeterminator;
    private ModuleJSRouteDeterminator $moduleJSRouteDeterminator;

    public function __construct(
        private Request $request,
        private PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules,
    ) {}

    public function response(): RouteCollection
    {
        $listingOfDirectoryOfRoadyModules = new ListingOfDirectoryOfRoadyModules($this->pathToDirectoryOfRoadyModules());
        $routes = [];
        foreach($listingOfDirectoryOfRoadyModules->pathsToRoadyModuleDirectories() as $pathToRoadyModuleDirectory) {
            $authoritiesJsonConfigurationReader = new AuthoritiesJsonConfigurationReader($pathToRoadyModuleDirectory);
            if(in_array($this->request()->url()->domain()->authority(), $authoritiesJsonConfigurationReader->authorityCollection()->collection())) {
                $moduleCSSRouteDeterminator = new ModuleCSSRouteDeterminator($pathToRoadyModuleDirectory);
                $moduleJSRouteDeterminator = new ModuleJSRouteDeterminator($pathToRoadyModuleDirectory);
                $moduleOutputRouteDeterminator = new ModuleOutputRouteDeterminator($pathToRoadyModuleDirectory);
                $routesJsonConfigurationReader = new RoutesJsonConfigurationReader($pathToRoadyModuleDirectory);
                foreach($moduleCSSRouteDeterminator->cssRoutes()->collection() as $cssRoute) {
                    $routes[] = $cssRoute;
                }
                foreach($moduleJSRouteDeterminator->cssRoutes()->collection() as $jsRoute) {
                    $routes[] = $jsRoute;
                }
                foreach($moduleOutputRouteDeterminator->outputRoutes()->collection() as $outputRoute) {
                    $routes[] = $outputRoute;
                }
                foreach($routesJsonConfigurationReader->configuredRoutes()->collection() as $configuredRoute) {
                    $routes[] = $configuredRoute;
                }
            }
        }
        return new RouteCollection(...$routes);
    }

}

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthoritiesJsonConfigurationReader

A AuthoritiesJsonConfigurationReader can return an AuthorityCollection
constructed from the authorities defined in a specified Module's
`authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthoritiesJsonConfigurationReader
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

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthoritiesJsonConfigurationWriter

A AuthoritiesJsonConfigurationWriter can write a specified AuthorityCollection
to a specified Module's `authorities.json` configuration file.

Warning: The write() method will overwrite an existing
`authorities.json` file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthoritiesJsonConfigurationWriter
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

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthoritiesJsonConfigurationEditor

A AuthoritiesJsonConfigurationEditor can add or remove Authorities
from a specified Modules `authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\paths\parts\url\Authority;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthoritiesJsonConfigurationEditor
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

### \Darling\RoadyModuleUtilities\interfaces\utilities\RoutesJsonConfigurationReader

A RoutesJsonConfigurationReader can return an RouteCollection
constructed from the Routes defined in a specified Module's
`routes.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;

interface RoutesJsonConfigurationReader
{

    public function read(
        PathToRoadyModuleDirectory $pathToRoadyModuleDirectory
    ): RouteCollection;

}

```
Example `routes.json`:

```
[
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\RoutesJsonConfigurationWriter

A RoutesJsonConfigurationWriter can write a specified RouteCollection
to a specified Module's `routes.json` configuration file.

Warning: The write() method will overwrite an existing
`routes.json` file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\PHPWebPaths\interfaces\collections\RouteCollection;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

interface RoutesJsonConfigurationWriter
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
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
]

```

### \Darling\RoadyModuleUtilities\interfaces\utilities\RoutesJsonConfigurationEditor

A RoutesJsonConfigurationEditor can add or remove Routes
from a specified Modules `routes.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\PHPWebPaths\interfaces\collections\RouteCollection;
use \Darling\PHPWebPaths\interfaces\paths\parts\url\Route;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;

interface RoutesJsonConfigurationEditor
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
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
]

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory

A PathToRoadyModuleDirectory defines a path to an existing Roady
Module's directory.

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

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules

A PathToDirectoryOfRoadyModules defines a path to an existing directory
where Roady Modules are expected to be located.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToExistingDirectory;
use \Stringable;

interface PathToDirectoryOfRoadyModules extends Stringable
{

   public function pathToExistingDirectory(): PathToExistingDirectory;

   public function __toString(): string;

}

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToExistingDirectory

A PathToExistingDirectory defines a path to an existing directory.

To ensure a path to an existing directory is always defined
the default path may be `/tmp`.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Darling\PHPTextTypes\interfaces\collections\SafeTextCollection;
use \Stringable;

interface PathToExistingDirectory extends Stringable
{

   public function SafeTextCollection(): SafeTextCollection;
   public function __toString(): string;

}

```
