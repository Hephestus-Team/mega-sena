<?php
    spl_autoload_register(function ($class)
    {
        spl_autoload_extensions('.class.php');
        $extension = spl_autoload_extensions();
        require_once(__DIR__ . "\\" . $class . $extension);
    });