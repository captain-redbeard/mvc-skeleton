<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Redbeard\Controllers;

use Redbeard\Core\Functions;

class Login extends Controller
{
    public function __construct()
    {
        //If logged in, redirect to member page
        if ($this->isLoggedIn()) {
            $this->redirect('members');
        }
    }
    
    public function index($username = '', $error = '')
    {
        //View page
        $this->view(
            ['template/navbar','login'],                                            //Page to load from Views
            [
                'page' => 'login',                                                  //Page, used in Views/templates/header.php
                'page_title' => 'Login to ' . $this->config('site.name'),           //Page title, used in Views/templates/header.php
                'page_description' => 'login site description',                     //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',                             //Page keywords, used in Views/templates/header.php
                'username' => htmlspecialchars($username),                          //Username to display
                'token' => $_SESSION['token'],                                      //XSS token is automatically generated
                'error' => $this->getErrorMessage($error)                           //Error message
            ],
            false                                                                   //Hide templates (header/footer)
        );
    }
    
    public function authenticate($parameters)
    {
        $error = $this->authenticateUser($parameters);
        
        if ($error === 0) {
            $this->redirect('members');
        } else {
            $this->index($_POST['username'], $error);
        }
    }
    
    private function authenticateUser($parameters)
    {
        if ($this->checkToken()) {
            $user = $this->model('User');
            
            return $user->login(
                $parameters['username'],
                $parameters['password']
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
                return 'Incorrect password.';
            case 11:
                return 'MFA Failed.';
            case 12:
                return 'To many login attempts, try again later.';
            case 13:
                return 'Username not found.';
            default:
                return '';
        }
    }
}
