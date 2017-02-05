<?php
/**
 * @author captain-redbeard
 * @since 20/01/17
 */
namespace Redbeard\Models;

use Redbeard\Core\Functions;
use Redbeard\Core\Database;
use Redbeard\Core\Session;
use Redbeard\Models\Role;
use Redbeard\ThirdParty\Google2FA;

class User
{
    public $user_id = null;
    public $user_guid = null;
    public $username = null;
    public $email = null;
    public $timezone = null;
    public $mfa_enabled = null;
    public $roles = null;
    
    public function getUser($userid)
    {
        $user_details = Database::select(
            "SELECT user_id, user_guid, username, email, timezone, mfa_enabled FROM users WHERE user_id = ?;",
            [$userid]
        );
        
        if (count($user_details) > 0) {
            $user = new User();
            $user->user_id = $user_details[0]['user_id'];
            $user->user_guid = $user_details[0]['user_guid'];
            $user->username = htmlspecialchars($user_details[0]['username']);
            $user->email = htmlspecialchars($user_details[0]['email']);
            $user->timezone = htmlspecialchars($user_details[0]['timezone']);
            $user->mfa_enabled = $user_details[0]['mfa_enabled'];
            $user->initRoles();
            return $user;
        } else {
            return false;
        }
    }
    
    protected function initRoles()
    {
        $this->roles = [];
        $query_roles = Database::select(
            "SELECT t1.role_id, t2.role_name
            
            FROM user_roles as t1
            JOIN roles as t2 ON t1.role_id = t2.role_id
            
            WHERE t1.user_id = ?;",
            [$this->user_id]
        );
        
        foreach ($query_roles as $role) {
            $this->roles[$role['role_name']] = Role::getRolePerms($role['role_id']);
        }
    }
    
    public function register($username, $password, $timezone)
    {
        $username = Functions::cleanInput($username);
        $timezone = Functions::cleanInput($timezone, 1);
        
        $validUsername = $this->validateUsername($username);
        $validPassword = $this->validatePassword($password);
        
        if ($validUsername !== 0) {
            return $validUsername;
        }
        
        if ($validPassword !== 0) {
            return $validPassword;
        }
        
        if ($timezone === -1) {
            return 6;
        }
        
        if (!isset($error)) {
            $existing = Database::select("SELECT user_id FROM users WHERE username = ?;", [$username]);
            if (count($existing) > 0) {
                return 7;
            }
            
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => PW_COST]);
            $guid = Functions::generateRandomString(32);
            $activation = Functions::generateRandomString(32);
            $secretkey = Google2FA::generateSecretKey();

            $userid = Database::insert(
                "INSERT INTO users (user_guid, username, password, secret_key, activation, timezone, modified) 
                    VALUES (?,?,?,?,?,?,NOW());",
                [
                    $guid,
                    $username,
                    $password,
                    $secretkey,
                    $activation,
                    $timezone
                ]
            );
            
            if ($insertid > -1) {
                Session::start();
                $_SESSION['user_id'] = $userid;
                $_SESSION['login_string'] = hash('sha512', $userid . $_SERVER['HTTP_USER_AGENT'] . $guid);
                $_SESSION[USESSION] = $this->getUser($userid, $passphrase);
                return 0;
            } else {
                return 8;
            }
        }
    }
    
    public function login($username, $password, $mfa)
    {
        $username = Functions::cleanInput($username);
        
        $validUsername = $this->validateUsername($username);
        $validPassword = $this->validatePassword($password);
        
        if ($validUsername !== 0) {
            return $validUsername;
        }
        
        if ($validPassword !== 0) {
            return $validPassword;
        }
        
        $existing = Database::select(
            "SELECT user_id, user_guid, password, mfa_enabled, secret_key FROM users WHERE username = ?;",
            [$username]
        );
        
        if (count($existing) > 0) {
            $attempts = Database::select(
                "SELECT made_date FROM login_attempts WHERE user_id = ? 
                    AND made_date > DATE_SUB(NOW(), INTERVAL 2 HOUR);",
                [$existing[0]['user_id']]
            );
            
            if (count($attempts) < MAX_LOGIN_ATTEMPTS) {
                if (password_verify($password, $existing[0]['password'])) {
                    if (password_needs_rehash(
                        $existing[0]['password'],
                        PASSWORD_DEFAULT,
                        ['cost' => PW_COST]
                    )) {
                        $newhash = password_hash(
                            $password,
                            PASSWORD_DEFAULT,
                            ['cost' => PW_COST]
                        );
                            
                        Database::update(
                            "UPDATE users SET password = ?, modified = now() WHERE user_id = ?;",
                            [
                                $newhash,
                                $existing[0]['user_id']
                            ]
                        );
                    }
                    
                    if ($existing[0]['mfa_enabled'] === -1) {
                        $rmfa = Google2FA::verifyKey($existing[0]['secret_key'], $mfa);
                        
                        if (!$rmfa) {
                            Database::update(
                                "INSERT INTO login_attempts(user_id, made_date) VALUES (?, NOW());",
                                [$existing[0]['user_id']]
                            );
                            
                            return 7;
                        }
                    }
                    
                    Session::start();
                    $_SESSION['user_id'] = $existing[0]['user_id'];
                    $_SESSION['login_string'] = hash(
                        'sha512',
                        $existing[0]['user_id'] . $_SERVER['HTTP_USER_AGENT'] . $existing[0]['user_guid']
                    );
                    $_SESSION[USESSION] = $this->getUser($_SESSION['user_id']);
                    
                    return 0;
                } else {
                    Database::update(
                        "INSERT INTO login_attempts(user_id, made_date) VALUES (?, NOW());",
                        [$existing[0]['user_id']]
                    );
                    return 6;
                }
            } else {
                return 8;
            }
        } else {
            return 9;
        }
    }
    
    public function update($username, $timezone)
    {
        $username = Functions::cleanInput($username);
        $timezone = Functions::cleanInput($timezone, 1);
        
        $validUsername = $this->validateUsername($username);
        
        if ($validUsername !== 0) {
            return $validUsername;
        }
        
        $existing = Database::select(
            "SELECT user_id, username FROM users WHERE username = ?;",
            [$username]
        );
        
        if (count($existing) > 0 && $existing[0]['username'] != $this->username) {
            return 2;
        }
        
        if (Database::update(
            "UPDATE users SET username = ?, timezone = ? WHERE user_id = ? AND user_guid = ?;",
            [
                $username,
                $timezone,
                $this->user_id,
                $this->user_guid
            ]
        )) {
                $_SESSION[USESSION] = $thisgetUser($this->user_id, $this->passphrase);
                return 0;
        } else {
            return 6;
        }
    }
    
    public function enableMfa($code1, $code2)
    {
        $code1 = Functions::cleanInput($_POST['code1'], 2);
        $code2 = Functions::cleanInput($_POST['code2'], 2);
        
        if ($code1 === null || $code2 === null) {
            return 1;
        }
        
        $user = Database::select(
            "SELECT secret_key, mfa_enabled FROM users WHERE user_id = ? AND user_guid = ?;",
            [
                $this->user_id,
                $this->user_guid
            ]
        );
        
        $result1 = Google2FA::verifyKey($user[0]['secret_key'], $code1);
        $result2 = Google2FA::verifyKey($user[0]['secret_key'], $code2);
        
        if ($result1 && $result2) {
            if (Database::update(
                "UPDATE users SET mfa_enabled = -1 WHERE user_id = ? AND user_guid = ?;",
                [
                    $this->user_id,
                    $this->user_guid
                ]
            )) {
                $this->mfa_enabled = -1;
                return 0;
            }
        } else {
            return 2;
        }
    }
    
    public function disableMfa()
    {
        Database::update(
            "UPDATE users SET mfa_enabled = 0 WHERE user_id = ? AND user_guid = ?;",
            [
                $this->user_id,
                $this->user_guid
            ]
        );
        
        $this->mfa_enabled = 0;
    }
    
    public function resetPassword($password, $new_password, $confirm_new_password)
    {
        if ($new_password != $confirm_new_password) {
            return 10;
        }
        
        $validPassword = $this->validatePassword($new_password);
        
        if ($validPassword !== 0) {
            return $validPassword;
        }
        
        $user = Database::select(
            "SELECT user_id, user_guid, password FROM users WHERE user_id = ? AND user_guid = ?;",
            [
                $this->user_id,
                $this->user_guid
            ]
        );

        if (count($user) > 0) {
            if (password_verify($password, $user[0]['password'])) {
                $newpass = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT,
                    ['cost' => PW_COST]
                );
                
                if (Database::update(
                    "UPDATE users SET password = ? WHERE user_id = ? AND user_guid = ?;",
                    [
                        $newpass,
                        $this->user_id,
                        $this->user_guid
                    ]
                )) {
                    return 0;
                } else {
                    return 11;
                }
            } else {
                return 12;
            }
        } else {
            return 13;
        }
    }
    
    public function hasPrivilege($perm)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function hasRole($role_name)
    {
        return isset($this->roles[$role_name]);
    }
    
    public function validateUsername($username)
    {
        if (strlen(trim($username)) < 1) {
            return 1;
        }
        
        if (strlen($username) > 255) {
            return 2;
        }
        
        return 0;
    }
    
    public function validatePassword($password)
    {
        if ($password === null || strlen($password) < 9) {
            return 3;
        }
        
        if (strlen($password) > 255) {
            return 4;
        }
        
        return 0;
    }
}
