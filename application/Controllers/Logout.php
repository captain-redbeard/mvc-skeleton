<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Redbeard\Controllers;

class Logout extends Controller
{
    public function index()
    {
        $this->logout();
        $this->redirect('login');
    }
}
