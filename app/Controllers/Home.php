<?php
/**
 * @author captain-redbeard
 * @since 20/01/17
 */
namespace Redbeard\Controllers;

class Home extends Controller
{
    public function __construct()
    {
        //Start session
        $this->startSession();
    }
    
    //Parameters are derived from the URL by exploding at slash
    public function index($param1 = null, $param2 = null)
    {
        //View page
        $this->view(
            ['template/navbar','home'],                             //Page(s) to load from Views
            [
                'page' => 'home',                                   //Page, used in Views/templates/header.php
                'page_title' => $this->config('site.name'),         //Page title, used in Views/templates/header.php
                'page_description' => 'site description',           //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',             //Page keywords, used in Views/templates/header.php
                'token' => $_SESSION['token'],                      //XSS token is automatically generated
                'param1' => $param1,                                //Example GET parameter 1
                'param2' => $param2                                 //Example GET parameter 2
            ],
            false                                                   //Hide templates (header/footer)
        );
    }
}
