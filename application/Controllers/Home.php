<?php
/**
 * @author captain-redbeard
 * @since 20/01/17
 */
namespace Demo\Controllers;

use Redbeard\Crew\Controller;

class Home extends Controller
{
    public function __construct()
    {
        //Start session
        $this->startSession();
    }
    
    public function index($param1 = null, $param2 = null)
    {
        //View page
        $this->view(
            [
                'template/navbar',
                'home'
            ],
            [
                'page' => 'home',
                'page_title' => $this->config('site.name'),
                'page_description' => 'site description',
                'page_keywords' => 'redbeard, example',
                'token' => $_SESSION['token'],
                'param1' => $param1,
                'param2' => $param2
            ],
            false
        );
    }
}
