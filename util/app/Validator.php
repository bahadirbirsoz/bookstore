<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8.06.2018
 * Time: 16:39
 */

namespace Bookstore\Util\App;


class Validator
{


    public function isValidEmail()
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}