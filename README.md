# RoadyModuleUtilities

Provides classes for working with Roady modules.

# Draft/Design Notes

Pseudo code for how this library will be used by Roady's
index.php in conjunction with the RoadyRoutingUtilities,
and RoadyTemplateUtilities libraries:

```
<?php

use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\classes\paths\PathToExisitingDirectory;
use \Darling\RoadyRoutingUtilities\classes\request\Request;
use \Darling\RoadyRoutingUtilities\classes\routing\Router;
use \Darling\RoadyTemplateUtilities\classes\paths\PathToDirectoryOfRoadyTemplates;

/**
 * The following is a rough draft/approximation of the actual
 * implementation of this file.
 *
 * The code in this file is likely to change.
 */


$ui = new RoadyUI(
    new PathToDirectoryOfRoadyTemplates(
        new PathToExisitingDirectory(
            new SafeTextCollection(
                new SafeText(new Text('path')),
                new SafeText(new Text('to')),
                new SafeText(new Text('roady')),
                new SafeText(new Text('templates')),
                new SafeText(new Text('directory'))
            )
        )
    ),
    new Router(
        /** @see comment ^ */
        new Request(),
        new PathToDirectoryOfRoadyModules(
            new PathToExisitingDirectory(
                new SafeTextCollection(
                    new SafeText(new Text('path')),
                    new SafeText(new Text('to')),
                    new SafeText(new Text('roady')),
                    new SafeText(new Text('modules')),
                    new SafeText(new Text('directory'))
                )
            )
        ),
    ),
);

echo $ui->__toString();

echo '<!-- Powered by [Roady](https://github.com/sevidmusic/Roady) -->

```

## Pseudo Router Definition

```
<?php

namespace \Darling\RoadyRoutingUtilities\classes\routing;

use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyRoutingUtilities\classes\request\Request;
use \Darling\RoadyRoutes\classes\collections\RouteCollection;

/**
 * The following is a rough draft/approximation of the actual
 * implementation of this file.
 *
 * The code in this file is likely to change.
 */

class Router
{

    public function __construct(
        private Request $request,
        private PathToDirectoryOfRoadyModules $pathToDirectoryOfRoadyModules,
    ) {}

    public function response(): RouteCollection
    {
        $listingOfDirectoryOfRoadyModules = new ListingOfDirectoryOfRoadyModules($this->pathToDirectoryOfRoadyModules());
        $routes = [];
        foreach($listingOfDirectoryOfRoadyModules->pathsToRoadyModuleDirectories() as $pathToRoadyModuleDirectory) {
            $moduleAuthoritiesJsonConfigurationReader = new ModuleAuthoritiesJsonConfigurationReader($pathToRoadyModuleDirectory);
            if(in_array($this->request()->url()->domain()->authority(), $moduleAuthoritiesJsonConfigurationReader->authorityCollection()->collection())) {
                $moduleCSSRouteDeterminator = new ModuleCSSRouteDeterminator($pathToRoadyModuleDirectory);
                foreach($moduleCSSRouteDeterminator->cssRoutes()->collection() as $cssRoute) {
                    $routes[] = $cssRoute;
                }
                $moduleJSRouteDeterminator = new ModuleJSRouteDeterminator($pathToRoadyModuleDirectory);
                foreach($moduleJSRouteDeterminator->cssRoutes()->collection() as $jsRoute) {
                    $routes[] = $jsRoute;
                }
                $moduleOutputRouteDeterminator = new ModuleOutputRouteDeterminator($pathToRoadyModuleDirectory);
                foreach($moduleOutputRouteDeterminator->outputRoutes()->collection() as $outputRoute) {
                    $routes[] = $outputRoute;
                }
                $moduleRoutesJsonConfigurationReader = new ModuleRoutesJsonConfigurationReader($pathToRoadyModuleDirectory);
                foreach($moduleRoutesJsonConfigurationReader->configuredRoutes()->collection() as $configuredRoute) {
                    $routes[] = $configuredRoute;
                }
            }
        }
        return new RouteCollection(...$routes);
    }

}

```

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
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
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
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
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
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name', 'name2'], [['named-psition-1', 1.7], ['named-position-2', 0.001]], 'relative/path'],
    [['name2'], [['named-position-2', 0.001]], 'relative/path'],
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
