<?php
require_once 'AutoLoader.php';
use Luracast\Restler\AutoLoader;

return call_user_func(function ()
{
    $loader = AutoLoader::instance();
    spl_autoload_register($loader);
    return $loader;
});

