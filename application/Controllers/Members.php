<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Demo\Controllers;

use Redbeard\Crew\Controller;

class Members extends Controller
{
    public function __construct()
    {
        //Require user to be logged in
        $this->requiresLogin();
        
        //Check permission
        //$this->requiresPermission('View Members');
    }
    
    public function index()
    {
        //View page
        $this->view(
            [
                'template/navbar',
                'members'
            ],
            [
                'page' => 'members',
                'page_title' => $this->config('site.name'),
                'page_description' => 'members site description',
                'page_keywords' => 'redbeard, example',
                'token' => $_SESSION['token'],
                'user' => $this->getUser()
            ],
            false
        );
    }
}
