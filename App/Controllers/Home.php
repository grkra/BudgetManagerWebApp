<?php
namespace App\Controllers;

use App\Auth;
use \Core\View;

/**
 * Home controller
 */
class Home extends Authenticated
{
    /**
     * Show the index page
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html', []);
    }
}
