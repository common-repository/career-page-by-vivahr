<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdf3cf586976dfa1b2f3ce0e1042abba1
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'VIVAHR\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'VIVAHR\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Inc',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdf3cf586976dfa1b2f3ce0e1042abba1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdf3cf586976dfa1b2f3ce0e1042abba1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdf3cf586976dfa1b2f3ce0e1042abba1::$classMap;

        }, null, ClassLoader::class);
    }
}
