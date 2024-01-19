<?php
namespace App\Controllers;

use \Core\View;

/**
 * Income controller
 */
class Income extends Authenticated
{
    /**
     * Show the balance page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Income/addIncome.html', []);
    }
}
