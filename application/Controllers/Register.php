<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Demo\Controllers;

use \DateTimeZone;
use Redbeard\Crew\Controller;

class Register extends Controller
{
    public function __construct()
    {
        //If logged in, redirect to member page
        if ($this->isLoggedIn()) {
            $this->redirect('members');
        }
        
        //Check token
        $this->requiresToken('register');
    }
    
    public function index($parameters = ['timezone' => '', 'username' => '', 'error' => ''])
    {
        //View page
        $this->view(
            [
                'template/navbar',
                'register'
            ],
            [
                'page' => 'register',
                'page_title' => 'Register to ' . $this->config('site.name'),
                'page_description' => 'register site description',
                'page_keywords' => 'redbeard, example',
                'timezones' => DateTimeZone::listIdentifiers(DateTimeZone::ALL),
                'timezone' => htmlspecialchars($parameters['timezone']),
                'username' => htmlspecialchars($parameters['username']),
                'token' => $_SESSION['token'],
                'error' => $parameters['error']
            ],
            false
        );
    }
    
    public function user($parameters = [])
    {
        //Get user model
        $user = $this->systemModel('User');
        
        //Attempt register
        $error = $user->register(
            $parameters['username'],
            $parameters['password'],
            $parameters['password'],
            $parameters['timezone']
        );
        
        //If success redirect, otherwise display error
        if ($error === true) {
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
