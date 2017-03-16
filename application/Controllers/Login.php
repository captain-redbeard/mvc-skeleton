<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Demo\Controllers;

use Redbeard\Crew\Controller;

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
            [
                'template/navbar',
                'login'
            ],
            [
                'page' => 'login',
                'page_title' => 'Login to ' . $this->config('site.name'),
                'page_description' => 'login site description',
                'page_keywords' => 'redbeard, example',
                'username' => htmlspecialchars($parameters['username']),
                'token' => $_SESSION['token'],
                'error' => $parameters['error']
            ],
            false
        );
    }
    
    public function authenticate($parameters = [])
    {
        //Get user model
        $user = $this->systemModel('User');
        
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
