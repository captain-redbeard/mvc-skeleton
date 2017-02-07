<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Redbeard\Controllers;

use \DateTimeZone;
use Redbeard\Core\Database;

class Register extends Controller
{
    public function __construct()
    {
        //If logged in, redirect to member page
        if ($this->isLoggedIn()) {
            $this->redirect('members');
        }
    }
    
    public function index($timezone = '', $username = '', $error = '')
    {
        //View page
        $this->view(
            ['template/navbar','register'],                                             //Page to load from Views
            [
                'page' => 'register',                                                   //Page, used in Views/templates/header.php
                'page_title' => 'Register to ' . $this->config('site.name'),            //Page title, used in Views/templates/header.php
                'page_description' => 'register site description',                      //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',                                 //Page keywords, used in Views/templates/header.php
                'timezones' => DateTimeZone::listIdentifiers(DateTimeZone::ALL),        //Timezones used in register page
                'timezone' => htmlspecialchars($timezone),                              //Selected timezone
                'username' => htmlspecialchars($username),                              //Selected username
                'token' => $_SESSION['token'],                                          //XSS token is automatically generated
                'error' => $this->getErrorMessage($error)                               //Error message
            ],
            false                                                                       //Hide templates (header/footer)
        );
    }
    
    public function user($parameters)
    {
        $error = $this->registerUser($parameters);
        
        if ($error === 0) {
            $this->redirect('members');
        } else {
            $this->index($_POST['timezone'], $_POST['username'], $error);
        }
    }
    
    private function registerUser($parameters)
    {
        if ($this->checkToken()) {
            $user = $this->model('User');
            
            return $user->register(
                $parameters['username'],
                $parameters['password'],
                $parameters['timezone']
            );
        } else {
            return -1;
        }
    }
    
    private function getErrorMessage($code)
    {
        switch ($code) {
            case -1:
                return 'Invalid token.';
            case 1:
                return 'Username must be at least 1 character.';
            case 2:
                return 'Username must be less than 256 characters.';
            case 3:
                return 'Password must be greater than 8 characters.';
            case 4:
                return 'Password must be less than 256 characters.';
            
            case 10:
                return 'You must select a Timezone.';
            case 11:
                return 'Username is already taken.';
            case 12:
                return 'Failed to create user, contact support.';
            default:
                return '';
        }
    }
}
