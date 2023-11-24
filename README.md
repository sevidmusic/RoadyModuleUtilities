# TODO

### PHPFilesystemPaths:

- \Darling\PHPFilesystemPaths\interfaces\paths\PathToExistingDirectory

### RoadyModuleUtilities:

- \Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection

- \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;

- \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules

- \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationEditor

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationReader

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationWriter

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationEditor

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationReader

- \Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationWriter

### RoadyTemplateUtilities:

- \Darling\RoadyTemplateUtilities\interfaces\collections\PathToRoadyHtmlFileTemplateFileCollection

- \Darling\RoadyTemplateUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyHtmlFileTemplates;

- \Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates

- \Darling\RoadyTemplateUtilities\interfaces\paths\PathToRoadyHtmlFileTemplateFile

### RoadyRoutingUtilities:

- \Darling\RoadyRoutingUtilities\interfaces\routing\Request;

- \Darling\RoadyRoutingUtilities\interfaces\routing\Response;

- \Darling\RoadyRoutingUtilities\interfaces\routing\Router;

### RoadyUIUtilities:

- \Darling\ROadyUIUtilities\interfaces\ui\RoadyUI;

# RoadyModuleUtilities

NOTE: At the moment I am using this file to plan the rest of
the re-write of Roady2.0, which will start with the implementation
of the RoadyModuleUtilities library. However, for the moment this
file may contain references to classes that are/or will be defined
by other libraries so I can organize my thoughts.

This file will change alot before the first release of this library.

Provides classes for working with Roady modules.

# Draft/Design Notes

Pseudo code for how this library will be used by Roady's
index.php in conjunction with the RoadyRoutingUtilities,
and RoadyTemplateUtilities libraries:

```
<?php

use \Darling\PHPFilesystemPaths\classes\paths\PathToExistingDirectory;
use \Darling\PHPTextTypes\classes\strings\SafeText;
use \Darling\PHPTextTypes\classes\strings\SafeTextCollection;
use \Darling\PHPTextTypes\classes\strings\Text;
use \Darling\RoadyModuleUtilities\classes\directory\listings\ListingOfDirectoryOfRoadyModules;
use \Darling\RoadyModuleUtilities\classes\paths\PathToDirectoryOfRoadyModules;
use \Darling\RoadyRoutingUtilities\classes\request\Request;
use \Darling\RoadyRoutingUtilities\classes\routing\Router;
use \Darling\RoadyTemplateUtilities\classes\paths\PathToDirectoryOfRoadyHtmlFileTemplates;

/**
 * The following is a rough draft/approximation of the actual
 * implementation of this file.
 *
 * The code in this file is likely to change.
 */


$ui = new RoadyUI(
    new Router(
        /** @see comment ^ */
        new Request(),
        new ListingOfDirectoryOfRoadyModules(
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
            )
        ),
    ),
    new PathToDirectoryOfRoadyHtmlFileTemplates(
        new PathToExisitingDirectory(
            new SafeTextCollection(
                new SafeText(new Text('path')),
                new SafeText(new Text('to')),
                new SafeText(new Text('roady')),
                new SafeText(new Text('tempaltes')),
                new SafeText(new Text('directory'))
            )
        )
    ),
);

echo $ui->__toString();

echo '<!-- Powered by [Roady](https://github.com/sevidmusic/Roady) -->

```

## Pseudo Router Definition

```
<?php

namespace \Darling\RoadyRoutingUtilities\interfaces\routing;

use \Darling\RoadyRoutingUtilities\interfaces\request\Request;
use \Darling\RoadyRoutes\interfaces\collections\RouteCollection;
use \Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules;
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
        private ListingOfDirectoryOfRoadyModules $listingOfDirectoryOfRoadyModules,
    ) {}

    public function response(): RouteCollection
    {
        $definedRoutes = [];

        foreach($this->listingOfDirectoryOfRoadyModules->pathsToRoadyModuleDirectories() as $pathToRoadyModuleDirectory) {

            $moduleAuthoritiesJsonConfigurationReader = new ModuleAuthoritiesJsonConfigurationReader($pathToRoadyModuleDirectory);

            if(in_array($this->request()->url()->domain()->authority(), $moduleAuthoritiesJsonConfigurationReader->authorityCollection()->collection())) {

                $moduleCSSRouteDeterminator = new ModuleCSSRouteDeterminator($pathToRoadyModuleDirectory);

                foreach($moduleCSSRouteDeterminator->cssRoutes()->collection() as $cssRoute) {

                    $definedRoutes[] = $cssRoute;

                }
                $moduleJSRouteDeterminator = new ModuleJSRouteDeterminator($pathToRoadyModuleDirectory);

                foreach($moduleJSRouteDeterminator->cssRoutes()->collection() as $jsRoute) {

                    $definedRoutes[] = $jsRoute;

                }
                $moduleOutputRouteDeterminator = new ModuleOutputRouteDeterminator($pathToRoadyModuleDirectory);

                foreach($moduleOutputRouteDeterminator->outputRoutes()->collection() as $outputRoute) {

                    $definedRoutes[] = $outputRoute;

                }
                $moduleRoutesJsonConfigurationReader = new ModuleRoutesJsonConfigurationReader($pathToRoadyModuleDirectory);

                foreach($moduleRoutesJsonConfigurationReader->configuredRoutes()->collection() as $configuredRoute) {

                    $definedRoutes[] = $configuredRoute;

                }
            }
        }
        $responseRoutes = [];

        foreach($routes as $routeIndex => $route) {

            if(

                in_array($request->name(), $route->nameCollection()->collection())
                ||
                in_array(new Name(new Text('global')), $route->nameCollection()->collection())

            ) {

                $responseRoutes[] = $route;

            }
        }

        return new RouteCollection(...$responseRoutes);

    }

}

```

## Pseudo RoadyUI Definition

```
<?php

namespace \Darling\ROadyUIUtilities\interfaces\ui;

use \Darling\RoadyRoutingUtilities\interfaces\routing\Router;
use \Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates;
use \Darling\RoadyTemplateUtilities\interfaces\paths\PathToRoadyHtmlFileTemplateFile;
use \Darling\RoadyRoutes\interfaces\sorters\RouteCollectionSorter;

/**
 * The following is a rough draft/approximation of the actual
 * implementation of this file.
 *
 * The code in this file is likely to change.
 */

class RoadyUI
{

    public function __construct(
        private Router $router,
        private PathToDirectoryOfRoadyHtmlFileTemplates $pathToDirectoryOfRoadyHtmlFileTemplates,
        private RouteCollectionSorter $routeCollectionSorter,
    ) {}

    public function render(): string
    {
        $pathToRoadyHtmlFileTemplateFile = new PathToRoadyHtmlFileTemplateFile(
            $router->request()->name() . '.html',
            new PathToDirectoryOfRoadyHtmlFileTemplates(
                new PathToExisitingDirectory(
                    new SafeTextCollection(
                        new SafeText(new Text('path')),
                        new SafeText(new Text('to')),
                        new SafeText(new Text('roady')),
                        new SafeText(new Text('tempaltes')),
                        new SafeText(new Text('directory'))
                    )
                )
            ),
        );

        $roadyTemplate = new RoadyHtmlFileTemplate($pathToRoadyHtmlFileTemplate);

        /** array<string, array<string, Route>> */
        $sortedRoutes = $this->routeCollectionSorter->sortByNamedPosition(
            $router->response()->routeCollection()
        );

        $templateString = $roadyTemplate->__toString();

        /** array<string, array<string, string>> */
        $routeOutputStrings = [];
        foreach($sortedRoutes as $routePositionName => $routes) {
            foreach($routes as $routePosition => $route) {
                $routeOutputStrings[$routePositionName] =
                $this->getRouteOutput($route);
            }
        }
        foreach($roadyTemplate->namedPositions() as $namedPosition) {
            $templateString = str_replace(
                '<' . $namedPosition . '></' . $namedPosition . '>',
                implode(PHP_EOL, $routeOutputStrings[$namedPosition]),
                $templateString,
            );
        }
        return $templateString;

    }

    private function getRouteOutput(Route $route): string
    {
        /**
         * @TODO Need $route->moduleName() so output can be
         * obtained via:
         *   $router->pathToDirectoryOfRoadyModules()->__toString() .
         *   $route->moduleName() .
         *   $route->relaitvePath()->__toString();
         *
         * Also, don't forget that `output`, `css`, and `js` routes
         * are handled differntly.
         * Also, don't forget that `output` routes that have the
         * extension `.php` will be executed as `php` and their
         * output will be captured via an `object buffer`.
         */
    }

    public function __toString(): string
    {
        return $this->render();
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


### \Darling\PHPFilesystemPaths\interfaces\paths\PathToExistingDirectory

A PathToExistingDirectory defines a path to an existing directory.

To ensure a path to an existing directory is always defined
the default path may be `/tmp`.

```
<?php

namespace \Darling\PHPFilesystemPaths\interfaces\paths;

use \Darling\PHPTextTypes\interfaces\collections\SafeTextCollection;
use \Stringable;

interface PathToExistingDirectory extends Stringable
{

   public function SafeTextCollection(): SafeTextCollection;
   public function __toString(): string;

}

```

### \Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates

A PathToDirectoryOfRoadyHtmlFileTemplates defines a path to an existing directory
where Roady Templates are expected to be located.

```
<?php

namespace \Darling\RoadyTemplateUtilities\interfaces\paths;

use \Darling\PHPFilesystemPaths\interfaces\paths\PathToExistingDirectory;
use \Stringable;

interface PathToDirectoryOfRoadyHtmlFileTemplates extends Stringable
{

   public function pathToExistingDirectory(): PathToExistingDirectory;

   public function __toString(): string;

}

```

### \Darling\RoadyTemplateUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyHtmlFileTemplates;

A ListingOfDirectoryOfRoadyHtmlFileTemplates defines a
PathToRoadyHtmlFileTemplateFileCollection of PathToRoadyHtmlFileTemplateFile
instances for the template files in the assigned
PathToDirectoryOfRoadyHtmlFileTemplates.

```
<?php

namespace \Darling\RoadyTemplateUtilities\interfaces\directory\listings;

use \Darling\RoadyTemplateUtilities\interfaces\collections\PathToRoadyHtmlFileTemplateFileCollection;
use \Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates;

interface ListingOfDirectoryOfRoadyHtmlFileTemplates
{

   public function pathToDirectoryOfRoadyHtmlFileTemplates(): PathToDirectoryOfRoadyHtmlFileTemplates;
   public function pathToRoadyHtmlFileTemplateFileCollection(): PathToRoadyHtmlFileTemplateFileCollection;

}


```

### \Darling\RoadyTemplateUtilities\interfaces\paths\PathToRoadyHtmlFileTemplateFile

A PathToRoadyHtmlFileTemplateFile defines a path to an existing
Roady Template file.

```
<?php

namespace \Darling\RoadyTemplateUtilities\interfaces\paths;

use \Darling\PHPTextTypes\interfaces\strings\Name;
use \Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates;
use \Stringable;

interface PathToRoadyHtmlFileTemplateFile extends Stringable
{

   public function templateName(): Name;
   public function pathToDirectoryOfRoadyHtmlFileTemplates(): PathToDirectoryOfRoadyHtmlFileTemplates;
   public function __toString(): string;

}

```

### \Darling\RoadyTemplateUtilities\interfaces\collections\PathToRoadyHtmlFileTemplateFileCollection

A PathToRoadyHtmlFileTemplateFileCollection defines a collection of
PathToRoadyHtmlFileTemplateFile instances.

```
<?php

namespace \Darling\RoadyTemplateUtilities\interfaces\collections;

use \Darling\RoadyTemplateUtilities\interfaces\paths\PathToRoadyHtmlFileTemplateFile;

interface PathToRoadyHtmlFileTemplateFileCollection
{

   /**
    * Return an array of PathToRoadyHtmlFileTemplateFile instances.
    *
    * @return array<int, PathToRoadyHtmlFileTemplateFile>
    *                                       An array of
    *                                       PathToRoadyHtmlFileTemplateFile
    *                                       instances.
    */
   public function collection(): array;

}

```
