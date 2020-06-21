<?php

namespace App\Models;

use \Core\DB;
use \App\Models\Model;
use \Core\Session;
use \Core\Cookie;
use \App\Models\UserSessions;


class Users extends Model
{

    private $logedIn, $sessionName, $cookieName;
    public static $currentLoggedInUser;


    public function __construct($user = '')
    {
        $this->table = 'Users';
        parent::__construct($this->table);
        $this->db = new \Core\DB;
        $this->sessionName = CURRENT_USER_SESSION_NAME;
        $this->cookieName = REMEMBER_ME_COOKIE_NAME;
        $this->softDelete = TRUE;
        if ($user != '') {
            if (is_int($user)) {
                $u = $this->db->findFirst('users', ['condition' => 'id=?', 'bind' => $user]);
            } else {
                $u = $this->db->findFirst('users', ['condition' => 'username=?', 'bind' => $user]);
            }
            if ($u) {
                $this->populateObjData($u);
            }
        }
    }


    public function findByUsername($username)
    {
        return $this->db->findFirst('users', ['condition' => 'username=?', 'bind' => $username]);
    }

    public function login($remember_me = false)
    {
        \Core\Session::set($this->sessionName, $this->id);
        if ($remember_me) {
            $hash = md5($this->id + rand(0, 100));
            $ua = \Core\Session::uagent_no_version();
            \Core\Cookie::set($this->cookieName, $hash, REMEMBER_ME_COOKIE_EXPIRY);
            $fields = ['session' => $hash, 'user_agent' => $ua, 'user_id' => $this->id];
            $this->query('delete from user_sessions where user_id=? and user_agent=?', [$this->id, $ua]);
            $this->db->insert('user_sessions', $fields);
        }
    }

    public static function currentLoggedInUser()
    {
        if (isset(self::$currentLoggedInUser)) {
            return self::$currentLoggedInUser;
        }
        if (\Core\Session::exists(CURRENT_USER_SESSION_NAME)) {
            $u = new Users((int) \Core\Session::get(CURRENT_USER_SESSION_NAME));
            self::$currentLoggedInUser = $u;
        }
        return self::$currentLoggedInUser;
    }

    public function logout()
    {
        $ua = \Core\Session::uagent_no_version();
        $this->query('delete from user_sessions where user_id=? and user_agent=?', [$this->id, $ua]);
        \Core\Session::delete(CURRENT_USER_SESSION_NAME);
        if (\Core\Cookie::exists(REMEMBER_ME_COOKIE_NAME)) {
            \Core\Cookie::delete(REMEMBER_ME_COOKIE_NAME);
        }
        self::$currentLoggedInUser = null;
        return true;
    }

    public static function loginUserFromCookie()
    {
        $user_session_model = new UserSessions();
        $user_session = $user_session_model->findFirst(['condition' => ['user_agent=?', 'session=?'], 'bind' => [Session::uagent_no_version(), Cookie::get(REMEMBER_ME_COOKIE_NAME)]]);
        $user_session_model->populateObjData($user_session); //dnd($user_session_model);////
        if ($user_session->user_id != '') {
            $user = new self((int) $user_session->user_id);
            $user->login();
            dnd($user);
        }
    }


    public function registerNewUser($param)
    {


        $this->assign($param);
        $this->deleted = 0;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->save();
    }

    public function acls()
    {
        if (empty($this->acl))
            return [];
        else
            return json_decode($this->acl, true);
    }

    public function __toString()
    {
        return $this->username . ' ' . $this->email;
    }
}
