<?php
/**
 * @author captain-redbeard
 * @since 11/02/17
 */
namespace Redbeard\Controllers;

class InvalidToken extends Controller
{
    public function __construct()
    {
        //Start session
        $this->startSession();
    }
    
    public function index($previousPage = null)
    {
        //View page
        $this->view(
            ['template/navbar','error/invalid-token'],
            [
                'page' => 'invalid-token',
                'page_title' => 'Invalid Token - ' . $this->config('site.name'),
                'page_description' => '',
                'page_keywords' => '',
                'previous_page' => $previousPage,
                'token' => $_SESSION['token']
            ],
            false
        );
    }
}
