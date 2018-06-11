<?php

namespace Bookstore\Util\Security;

class Csrf extends \Bookstore\Util\App\Component
{


    public function register($recordIdentifier = "")
    {
        $route = \Bookstore\Util\App::getInstance()->getRouter()->getMatchRoute();
        $controllerName = $route->getController();
        $actionName = $route->getAction();
        $userId = $this->auth->isMember() ? $this->auth->getMember()->member_id : -1;
        return sha1($controllerName . $this->session->getSessionId() . $actionName . $recordIdentifier . $userId);
    }

    public function check($hash, $recordIdentifier = "")
    {
        $route = \Bookstore\Util\App::getInstance()->getRouter()->getMatchRoute();
        $controllerName = $route->getController();
        $actionName = $route->getAction();
        $userId = $this->auth->isMember() ? $this->auth->getMember()->member_id : -1;
        return $hash == sha1($controllerName . $this->session->getSessionId() . $actionName . $recordIdentifier . $userId);
    }

}