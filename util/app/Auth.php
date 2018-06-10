<?php

namespace Bookstore\Util\App;

use Bookstore\Model\Member;

class Auth extends Component
{

    const SESSION_KEY_REDIRECT = "redirect_after_login";
    const SESSION_KEY_USER_ID = "bookstore_user_id";

    const ERR_USER_NOT_FOUND = 1;
    const ERR_PASSWORD_WRONG = 2;

    protected $error;

    /**
     * @var Member
     */
    protected $member;

    public function __construct()
    {
        if ($this->session->has(self::SESSION_KEY_USER_ID)) {
            $this->member = Member::find($this->session->get(self::SESSION_KEY_USER_ID));
        }
    }


    public function isMember()
    {
        return isset($this->member) && $this->member;
    }

    /**
     * @return Member
     */
    public function getMember()
    {
        return $this->member;
    }


    public function hasError()
    {
        return isset($this->error);
    }

    public function getErrorCode()
    {
        return $this->error;
    }

    public function login($email, $password)
    {
        /* @var $member Member */
        $member = Member::findOneByEmail($email);
        if (!$member) {
            $this->error = self::ERR_USER_NOT_FOUND;
            return false;
        }
        if ($member->pass != $this->hash(trim($password))) {
            $this->error = self::ERR_PASSWORD_WRONG;
            return false;
        }

        $this->member = $member;
        $this->session->set(self::SESSION_KEY_USER_ID, $this->member->member_id);
        return true;

    }

    public function hash($pass)
    {
        if (defined("PASSWORD_SALT_SUFFIX")) {
            return sha1($pass . self::PASSWORD_SALT_SUFFIX);
        }
        return sha1($pass);
    }

}