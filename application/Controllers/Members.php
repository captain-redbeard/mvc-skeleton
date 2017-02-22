<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Redbeard\Controllers;

use Redbeard\Core\Functions;
use Redbeard\Core\Database;

class Members extends Controller
{
    public function __construct()
    {
        //Require user to be logged in
        $this->requiresLogin();
        
        //Check permission
        $this->requiresPermission('View Members');
    }
    
    public function index()
    {
        //View page
        $this->view(
            ['template/navbar','members'],                              //Page to load from Views
            [
                'page' => 'members',                                    //Page, used in Views/templates/header.php
                'page_title' => $this->config('site.name'),             //Page title, used in Views/templates/header.php
                'page_description' => 'members site description',       //Page description, used in Views/templates/header.php
                'page_keywords' => 'redbeard, example',                 //Page keywords, used in Views/templates/header.php
                'token' => $_SESSION['token'],                          //XSS token is automatically generated and lasts 5 minutes
                'user' => $_SESSION[$this->config('app.user_session')]  //User
            ],
            false                                                       //Hide templates (header/footer)
        );
    }
}
