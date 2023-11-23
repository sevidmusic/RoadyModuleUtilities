# RoadyModuleUtilities

Provides classes for working with Roady modules.

### Draft/Design Notes


Needed:

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthorityJsonConfigurationReader

A AuthorityJsonConfigurationReader can return an AuthorityCollection
constructed from the authorities defined in a specified module's
`authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthorityJsonConfigurationReader
{
    public function read(PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): AuthorityCollection;
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

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthorityJsonConfigurationWriter

A AuthorityJsonConfigurationWriter can write a specified AuthorityCollection
to a specified module's `authorities.json` configuration file.

Warning: The write() method will overwrite an existing
`authorities.json` file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthorityJsonConfigurationWriter
{
    public function write(AuthorityCollection $authorityCollection, PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): bool;
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

### \Darling\RoadyModuleUtilities\interfaces\utilities\AuthorityJsonConfigurationEditor

A AuthorityJsonConfigurationEditor can add or remove Authorities
from a specified modules `authorities.json` configuration file.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\utilities;

use \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory;
use \Darling\PHPWebPaths\interfaces\paths\parts\url\Authority;
use \Darling\PHPWebPaths\interfaces\collections\AuthorityCollection;

interface AuthorityJsonConfigurationEditor
{
    public function add(Authority $authority, PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): bool;
    public function addMultiple(AuthorityCollection $authorityCollection, PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): bool;
    public function remove(Authority $authority, PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): bool;
    public function removeMultiple(AuthorityCollection $authorityCollection, PathToRoadyModuleDirectory $pathToRoadyModuleDirectory): bool;
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

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToRoadyModuleDirectory

Path to an existing Roady module's directory.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Stringable;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules;
use \Darling\PHPTextTypes\interfaces\strings\Name;

interface PathToRoadyModuleDirectory extends Stringable
{

   public function moduleName(): Name;
   public function pathToDirectoryOfRoadyModules(): PathToDirectoryOfRoadyModules;
   public function __toString(): string;

}

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToDirectoryOfRoadyModules

A PathToDirectoryOfRoadyModules defines a path to an existing directory
where roady modules are expected to be located.

```
<?php

namespace \Darling\RoadyModuleUtilities\interfaces\paths;

use \Stringable;
use \Darling\RoadyModuleUtilities\interfaces\paths\PathToExistingDirectory;

interface PathToDirectoryOfRoadyModules extends Stringable
{

   public function pathToExistingDirectory(): PathToExistingDirectory;

   public function __toString(): string;

}

```

### \Darling\RoadyModuleUtilities\interfaces\paths\PathToExistingDirectory

A PathToExistingDirectory defines a path to an existing directory.

To ensure a path to an existing directory is always defined
the defined path may be `/tmp`.

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
