# Summary:

# PHPFilesystemPaths Library:

### `\Darling\PHPFilesystemPaths\interfaces\paths\PathToExistingDirectory`:

Defines a path to an existing directory.

# RoadyModuleUtilities Library:

### `\Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules`:

Defines a path to a directory of Roady Modules.

### `\Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory`:

Defines a path to an existing Roady Module's directory.

### `\Darling\RoadyModuleUtilities\interfaces\collections\PathToRoadyModuleDirectoryCollection`:

Defines a collection of `PathToRoadyModuleDirectory` instances.

### `\Darling\RoadyModuleUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyModules`:

Defines a directory listing of a specified `PathToDirectoryOfRoadyModules` in the form of a `PathToRoadyModuleDirectoryCollection`

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationReader`:

Reads AuthorityCollection from a module's `authorities.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationWriter`:

Writes AuthorityCollection to a module's `authorities.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleAuthoritiesJsonConfigurationEditor`:

Edits Authorities in a module's `authorities.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationReader`:

Reads RouteCollection from a module's `routes.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationWriter`:

Writes RouteCollection to a module's `routes.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleRoutesJsonConfigurationEditor`:

Edits Routes in a module's `routes.json`.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleCSSRouteDeterminator`:

Determines CSS routes for a module.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleJSRouteDeterminator`:

Determines JS routes for a module.

### `\Darling\RoadyModuleUtilities\interfaces\utilities\ModuleOutputRouteDeterminator`:

Determines output routes for a module.


# RoadyTemplateUtilities Library:

### `\Darling\RoadyTemplateUtilities\interfaces\paths\PathToDirectoryOfRoadyHtmlFileTemplates`:

Defines a path to a directory of Roady HTML templates.

### `\Darling\RoadyTemplateUtilities\interfaces\paths\PathToRoadyHtmlFileTemplateFile`:

Defines a path to an existing Roady HTML template file.

### `\Darling\RoadyTemplateUtilities\interfaces\collections\PathToRoadyHtmlFileTemplateFileCollection`:

Defines a collection of `PathToRoadyHtmlFileTemplateFile` instances.

### `\Darling\RoadyTemplateUtilities\interfaces\directory\listings\ListingOfDirectoryOfRoadyHtmlFileTemplates`:

Defines a directory listing of a specified `PathToDirectoryOfRoadyHtmlFileTemplates` in the form of a `PathToRoadyHtmlFileTemplateFileCollection`


# RoadyRoutingUtilities Library:

### `\Darling\RoadyRoutingUtilities\interfaces\routing\Request`:

Interface representing an HTTP request.

### `\Darling\RoadyRoutingUtilities\interfaces\routing\Response`:

Interface representing an HTTP response.

### `\Darling\RoadyRoutingUtilities\interfaces\routing\Router`:

Interface for routing requests.


# RoadyUIUtilities Library:

### `\Darling\RoadyUIUtilities\interfaces\ui\RoadyUI`:

Interface representing the user interface of Roady.

######################################################################
############################ Roady 2.0 ###############################
######################################################################


# RoadyModuleUtilities

NOTE: At the moment I am using this file to plan the rest of
the re-write of Roady2.0, which will start with the implementation
of the RoadyModuleUtilities library. However, for the moment this
file may contain references to classes that are/or will be defined
by other libraries so I can organize my thoughts.

This file will change alot before the first release of this library.

The RoadyModuleUtilities library will provide classes for working with
Roady modules.

# Draft/Design Notes

Pseudo code for how this library will be used by Roady's
index.php in conjunction with the RoadyRoutingUtilities,
and RoadyTemplateUtilities libraries:

```
<?php

# Roady's index.php

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

### Pseudo Router Definition

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

### Pseudo RoadyUI Definition

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
                implode(PHP_EOL, ($routeOutputStrings[$namedPosition] ?? [])),
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
         $targetFilePath = $router->pathToDirectoryOfRoadyModules()->__toString() .
                           DIRECTORY_SEPARATOR .
                           $route->moduleName() .
                           DIRECTORY_SEPARATOR .
                           $route->relaitvePath()->__toString();
        if($this->fileIsAPhpFile($targetFilePath)) {
            ob_start();
            include($targetFilePath);
            return ob_get_clean();
        }
        if($this->fileIsACssFile($targetFilePath) {
            return '<link rel="stylesheet" type="text/css" href="' . $this->determineRouteWebPath($this->router()->request()->domain(), $route) . '" />';
        }
        if($this->fileIsAJsFile($targetFilePath) {
            return '<script type="text/javascript" src="' . $this->determineRouteWebPath($this->router()->request()->domain(), $route) . '"></script>';
        }
        return strval(file_get_contents($targetFilePath));

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

