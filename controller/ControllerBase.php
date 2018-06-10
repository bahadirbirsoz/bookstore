<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8.06.2018
 * Time: 17:32
 */

namespace Bookstore\Controller;


use Bookstore\Util\App\Auth;
use Bookstore\Util\Mvc\Controller;

class ControllerBase extends Controller
{
    protected function requireUser()
    {
        if (!$this->auth->isMember()) {
            $this->session->set(Auth::SESSION_KEY_REDIRECT, $this->request->getUrl());
            $this->response->redirect('/login');
        }
    }


    function initialize()
    {
        $this->view->setDefaultMasterPage('member');
        $this->view->setDefaultLayoutPage('main');

        if ($this->auth->isMember()) {
            $this->view->memberFname = $this->auth->getMember()->fname;
            $this->view->memberLname = $this->auth->getMember()->lname;
        }

        $name = get_class($this) . "::PUBLIC_PAGE";
        if (!defined($name)) {
            $this->requireUser();
        }
    }
}