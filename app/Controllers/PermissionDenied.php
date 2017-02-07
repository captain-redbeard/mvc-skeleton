<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Redbeard\Controllers;

class PermissionDenied extends Controller
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
            ['template/navbar','permission-denied'],                //Page to load from Views
            [
                'page' => 'home',                                   //Page, used in Views/templates/header.php
                'page_title' => $this->config('site.name'),         //Page title, used in Views/templates/header.php
                'page_description' => 'site description',           //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',             //Page keywords, used in Views/templates/header.php
                'token' => $_SESSION['token']                       //XSS token is automatically generated
            ],
            false                                                   //Hide templates (header/footer)
        );
    }
}
