<?php
/**
 * @author captain-redbeard
 * @since 20/01/17
 */
namespace Redbeard\Controllers;

class Home extends Controller
{
    //Parameters are dervied from the URL by exploding at slash
    public function index($param1 = null, $param2 = null)
    {
        //Param1 will contain post values if they are set
        
        //Start session
        $this->startSession();
        
        //View page
        $this->view(
            ['home'],
            [
                'page' => 'home',
                'page_title' => SITE_NAME,
                'token' => $_SESSION['token'],
                'param1' => $param1,
                'param2' => $param2,
                'error' => ''
            ],
            false
        );
    }
}
