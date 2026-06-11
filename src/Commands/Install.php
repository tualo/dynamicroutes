<?php

namespace Tualo\Office\DynamicRoutes\Commands;

use Tualo\Office\Basic\ICommandline;
use Tualo\Office\Basic\CommandLineInstallSQL;


class Install extends CommandLineInstallSQL  implements ICommandline
{
    public static function getDir(): string
    {
        return dirname(__DIR__, 1);
    }
    public static $shortName  = 'dynamicroutes';
    public static $files = [


        'ddl'                    => 'setup ddl',


    ];
}
