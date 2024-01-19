<?php
namespace App\Controllers;

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
    public function newAction()
    {
        View::renderTemplate('Home/home.html', []);
    }
}
