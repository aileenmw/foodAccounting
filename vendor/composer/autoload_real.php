<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit4b6aebd05bb4aef4a7d12e50c94f867e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit4b6aebd05bb4aef4a7d12e50c94f867e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit4b6aebd05bb4aef4a7d12e50c94f867e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit4b6aebd05bb4aef4a7d12e50c94f867e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
