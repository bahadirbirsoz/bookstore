<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8.06.2018
 * Time: 10:23
 */

namespace Bookstore\Controller;

use Bookstore\Util\App\Auth;

class LoginController extends ControllerBase
{
    const PUBLIC_PAGE = TRUE;

    public function mainAction()
    {
        
        $this->view->setLayout("empty");
        $this->view->setMasterPage("guest");
        $this->view->error = false;
        if ($this->auth->isMember()) {

            $this->session->delete(Auth::SESSION_KEY_REDIRECT);
            $this->response->redirect("/");
        }
        if ($this->request->isPost() && $this->isLoginValid()) {

            if ($this->auth->login($this->request->getPost('email'), $this->request->getPost('password'))) {
                $to = $this->session->has(Auth::SESSION_KEY_REDIRECT) ? $this->session->get(Auth::SESSION_KEY_REDIRECT) : '/';
                $this->response->redirect($to);
            } else {
                $this->view->error = true;
            }
        }
    }

    public function isLoginValid()
    {
        return $this->request->hasPost('email', 'password') && $this;
    }
}