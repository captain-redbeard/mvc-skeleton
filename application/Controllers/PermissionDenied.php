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
    
    public function index()
    {
        //View page
        $this->view(
            ['template/navbar','error/permission-denied'],
            [
                'page' => 'permission-denied',
                'page_title' => 'Permission denied - ' . $this->config('site.name'),
                'page_description' => '',
                'page_keywords' => '',
                'token' => $_SESSION['token']
            ],
            false
        );
    }
}
