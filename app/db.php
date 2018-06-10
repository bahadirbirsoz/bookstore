<?php

switch (php_uname('n')) {
    case 'bahadir': //DEV
        define("HOST", "http://mynet.lcl");
        define("DB_HOST", "127.0.0.1");
        define("DB_USER", "root");
        define("DB_NAME", "bookstrore");
        define("DB_PASS", "");
        break;
    default:
        //PROD
        define("HOST", "https://www.ipfexperts.bi-mobilecontentdelivery.com");
        define("DB_HOST", "mysql-prod");
        define("DB_USER", "ipfexpert-mcd-pd");
        define("DB_NAME", "ipfexpert-mcd-pd");
        define("DB_PASS", "ca8vW4w3lgPjrzqo");
        break;
}

