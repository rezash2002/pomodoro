<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3bfea00742b96e0278c635e2def77dce
{
    public static $files = array (
        '256c1545158fc915c75e51a931bdba60' => __DIR__ . '/..' . '/lcobucci/jwt/compat/class-aliases.php',
        '0d273777b2b0d96e49fb3d800c6b0e81' => __DIR__ . '/..' . '/lcobucci/jwt/compat/json-exception-polyfill.php',
        'd6b246ac924292702635bb2349f4a64b' => __DIR__ . '/..' . '/lcobucci/jwt/compat/lcobucci-clock-polyfill.php',
    );

    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Lcobucci\\JWT\\' => 13,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3bfea00742b96e0278c635e2def77dce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3bfea00742b96e0278c635e2def77dce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3bfea00742b96e0278c635e2def77dce::$classMap;

        }, null, ClassLoader::class);
    }
}
