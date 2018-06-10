<?php


spl_autoload_register(function ($arg) {
    /**
     * In order to keep directory names lowercase and class name case-sensitive,
     * converting namespace to lowercase will give us directory
     */

    $argArr = explode('\\', $arg);
    $className = array_pop($argArr);
    if ($argArr[0] != "Bookstore") {
        return;
    }
    unset($argArr[0]);
    $path = APP_PATH . DS . strtolower(implode(DS, $argArr)) . DS . $className . ".php";
    if (file_exists($path)) {
        require_once $path;
    } else {
        throw new Exception("Class $arg not found in $path");
    }


});



