<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19770ca7ad73b90414b6c9f113239493
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'Reksakarya\\HcSync\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Reksakarya\\HcSync\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit19770ca7ad73b90414b6c9f113239493::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19770ca7ad73b90414b6c9f113239493::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit19770ca7ad73b90414b6c9f113239493::$classMap;

        }, null, ClassLoader::class);
    }
}
