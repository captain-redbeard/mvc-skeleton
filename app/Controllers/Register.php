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
        
        //Check token
        $this->requiresToken();
    }
    
    public function index($parameters = ['timezone' => '', 'username' => '', 'error' => ''])
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
                'timezone' => htmlspecialchars($parameters['timezone']),                //Selected timezone
                'username' => htmlspecialchars($parameters['username']),                //Selected username
                'token' => $_SESSION['token'],                                          //XSS token is automatically generated
                'error' => $parameters['error']                                         //Error message
            ],
            false                                                                       //Hide templates (header/footer)
        );
    }
    
    public function user($parameters)
    {
        //Get user model
        $user = $this->model('User');
        
        //Attempt register
        $error = $user->register(
            $parameters['username'],
            $parameters['password'],
            $parameters['timezone']
        );
        
        //If success redirect, otherwise display error
        if ($error === 0) {
            $this->redirect('members');
        } else {
            $this->index([
                'timezone' => $parameters['timezone'],
                'username' => $parameters['username'],
                'error' => $error
            ]);
        }
    }
}
