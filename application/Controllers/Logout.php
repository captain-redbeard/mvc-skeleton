<?php
/**
 * @author captain-redbeard
 * @since 07/02/17
 */
namespace Demo\Controllers;

use Redbeard\Crew\Controller;

class Logout extends Controller
{
    public function index()
    {
        $this->logout();
        $this->redirect('login');
    }
}
