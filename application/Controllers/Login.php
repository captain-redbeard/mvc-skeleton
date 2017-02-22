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
        
        //Check token
        $this->requiresToken('login');
    }
    
    public function index($parameters = ['username' => '', 'error' => ''])
    {
        //View page
        $this->view(
            ['template/navbar','login'],                                            //Page to load from Views
            [
                'page' => 'login',                                                  //Page, used in Views/templates/header.php
                'page_title' => 'Login to ' . $this->config('site.name'),           //Page title, used in Views/templates/header.php
                'page_description' => 'login site description',                     //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',                             //Page keywords, used in Views/templates/header.php
                'username' => htmlspecialchars($parameters['username']),            //Username to display
                'token' => $_SESSION['token'],                                      //XSS token is automatically generated
                'error' => $parameters['error']                                     //Error message
            ],
            false                                                                   //Hide templates (header/footer)
        );
    }
    
    public function authenticate($parameters = [])
    {
        //Get user model
        $user = $this->model('User');
        
        //Attempt login
        $error = $user->login(
            $parameters['username'],
            $parameters['password']
        );
        
        //If success redirect, otherwise display error
        if ($error === 0) {
            $this->redirect('members');
        } else {
            $this->index([
                'username' => $parameters['username'],
                'error' => $error
            ]);
        }
    }
}
