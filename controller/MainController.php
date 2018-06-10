<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 31.05.2018
 * Time: 23:14
 */

namespace Bookstore\Controller;


class MainController extends ControllerBase
{
    public function mainAction()
    {

        if ($this->auth->isMember()) {
            $this->response->redirect('/book');
        } else {
            $this->session->set(Auth::SESSION_KEY_REDIRECT, $this->request->getUrl());
            $this->response->redirect('/login');
        }
    }
}