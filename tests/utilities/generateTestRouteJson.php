<?php

use Darling\PHPTextTypes\classes\collections\NameCollection;
use Darling\PHPTextTypes\classes\collections\SafeTextCollection;
use Darling\PHPTextTypes\classes\strings\Name;
use Darling\PHPTextTypes\classes\strings\SafeText;
use Darling\PHPTextTypes\classes\strings\Text;
use Darling\RoadyRoutes\classes\collections\NamedPositionCollection;
use Darling\RoadyRoutes\classes\identifiers\NamedPosition;
use Darling\RoadyRoutes\classes\identifiers\PositionName;
use Darling\RoadyRoutes\classes\paths\RelativePath;
use Darling\RoadyRoutes\classes\routes\Route;
use Darling\RoadyRoutes\classes\settings\Position;

include(str_replace('tests' . DIRECTORY_SEPARATOR . 'utilities', '', __DIR__) . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

function randomFloat(): float
{
    return floatval((rand(0, 1) ? rand(-1, 1) : strval(rand(-100, 100)) . '.' . (rand(0, 1) ? strval(rand(0, 100)) : 0)));
}

$moduleNameStrings = [
    'empty-module',
    'module-defines-css',
    'module-defines-css-js-and-output',
    'module-defines-empty-routes-json-configuration-file',
    'module-defines-invalid-routes-json-configuration-file',
    'module-defines-js',
    'module-defines-more-realistic-setup',
    'module-defines-output',
    'module-defines-valid-routes-json-configuration-file-that-defines-routes-and-other-types-of-values',
    'module-defines-valid-routes-json-configuration-file-that-does-not-define-any-routes',
    'module-defines-valid-routes-json-configuration-file-that-only-defines-routes',
    'module-organizes-files-into-sub-directories',
    'module-does-not-exist',
];

$outputFileExtensions = ['php', 'html', 'txt'];
$routes = [];

for($i = 0; $i < rand(0, 1); $i++) {
    $routes[] = new Route(
        new Name(new Text($moduleNameStrings[array_rand($moduleNameStrings)])),
        (
            rand(0, 9) === 0
            ? new NameCollection()
            : new NameCollection(
                new Name(new Text('responds-to-request')),
                (
                    rand(0, 9) !== 0
                    ? new Name(new Text('responds-to-another-request'))
                    : new Name(new Text('responds-to-all-requests-because-name-contains-the-string-global'))
                ),
                new Name(new Text('responds-to-another-request-2')),
            )
        ),
        (
            rand(0, 9) === 0
            ? new NamedPositionCollection()
            : new NamedPositionCollection(
                new NamedPosition(
                     new PositionName(
                         new Name(
                         new Text('section-' . strval(rand(0, 2)))
                         )
                     ),
                     new Position(randomFloat()),
                 ),
                 new NamedPosition(
                     new PositionName(
                         new Name(
                             new Text('section-' . strval(rand(0, 2)))
                         )
                     ),
                     new Position(randomFloat()),
                 ),
                 new NamedPosition(
                     new PositionName(
                         new Name(
                             new Text('section-' . strval(rand(0, 2)))
                         )
                     ),
                     new Position(randomFloat()),
                 ),
             )
        ),
        new RelativePath(
            new SafeTextCollection(
                new SafeText(new Text('path')),
               new SafeText(new Text('to')),
                new SafeText(new Text('output-file.' . $outputFileExtensions[array_rand($outputFileExtensions)])),
            ),
        ),
    );

}

$routeArrays = [];

foreach ($routes as $key => $route) {
    if(rand(0, 9) === 0) {
        continue;
    }
    $routeArrays[$key] = [];
    if(rand(0, 3) === 0) {
        $moduleNameKey = (rand(0, 2) === 0 ? 'bad-index' : 'module-name');
        $routeArrays[$key][$moduleNameKey] = (rand(0, 9) === 0 ? '' : $route->moduleName()->__toString());
    }
    if(rand(0, 9) !== 0) {
        $respondsToKey = (rand(0, 2) === 0 ? 'bad-index' : 'responds-to');
        $routeArrays[$key][$respondsToKey] = [];
        foreach($route->nameCollection()->collection() as $name) {
            if(!is_array($routeArrays[$key][$respondsToKey]) || rand(0, 9) === 0) { continue; }
            $routeArrays[$key][$respondsToKey][] = $name->__toString();
        }
    }
    if(rand(0, 9) !== 0) {
        $namedPositionsKey = (rand(0, 2) === 0 ? 'bad-index' : 'named-positions');
        $routeArrays[$key][$namedPositionsKey] = [];
        foreach($route->namedPositionCollection()->collection() as $namedPosition) {
            if(is_array($routeArrays[$key][$namedPositionsKey])) {
                $routeArrays[$key][$namedPositionsKey][] = [
                    'position-name' => $namedPosition->positionName()->__toString(),
                    'position' => $namedPosition->position()->floatValue(),
                ];
            }
        }
    }
    if(rand(0, 9) !== 0) {
        $relativePathKey = (rand(0, 2) === 0 ? 'bad-index' : 'relative-path');
        $routeArrays[$key][$relativePathKey] = $route->relativePath()->__toString();
    }
}

var_dump($routeArrays, json_encode($routeArrays));

