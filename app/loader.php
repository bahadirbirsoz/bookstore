<?php


spl_autoload_register(function ($arg) {
    /**
     * In order to keep directory names lowercase and class name case-sensitive,
     * converting namespace to lowercase will give us directory
     */

    $argArr = explode('\\', $arg);
    $className = array_pop($argArr);

    if ($argArr[0] == "Bookstore") {

        $path = strtolower(APP_PATH . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $argArr));
        echo $path . DIRECTORY_SEPARATOR . $className . ".php";
    }


});



