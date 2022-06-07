<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcf2aca4d99d61e0d80e3ff2926170ac6
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PrestaShop\\Module\\Smsto\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PrestaShop\\Module\\Smsto\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcf2aca4d99d61e0d80e3ff2926170ac6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcf2aca4d99d61e0d80e3ff2926170ac6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitcf2aca4d99d61e0d80e3ff2926170ac6::$classMap;

        }, null, ClassLoader::class);
    }
}