<?php

namespace Bookstore\Controller;

class LogoutController extends ControllerBase
{

    public function mainAction()
    {
        $this->session->clear();
        $this->response->redirect('/');
    }
}