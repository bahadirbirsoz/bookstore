<?php

use Bookstore\Util\App;

$app = \Bookstore\Util\App::getInstance();
$router = $app->getRouter();
$router->setDefaultNamespace('Bookstore\Controller');


$router->register(
    App\Route::_('')
        ->setController('main')
        ->setAction('main')
);


$router->register(
    App\Route::_('ozel-bir-pattern-olsun-bu/:action/:param')
        ->setController('haber')
);


$router->register(App\Route::_(':controller')->setAction('main'));

$router->register(
    App\Route::_(':controller/:action')
);

$router->register(
    App\Route::_(':controller/:action/:param')
);


$router->register(
    App\Route::_(':controller/:action/:param/:param')
);


$router->register(
    App\Route::_(':controller/:action/:params')
);

$router->setNotFoundRoute(
    App\Route::_('hata/404')
        ->setController('error')
        ->setAction('main')
        ->setParams([404])
);





