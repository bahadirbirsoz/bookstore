<?php

namespace Bookstore\Model;

use Bookstore\Util\Mvc\Model;

class Member extends Model
{
    public $member_id;
    public $fname;
    public $lname;
    public $password;
    public $email;
}