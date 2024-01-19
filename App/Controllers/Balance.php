<?php
namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 */
class Balance extends Authenticated
{
    /**
     * Show the balance page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Balance/balance.html', []);
    }
}
