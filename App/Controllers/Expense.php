<?php
namespace App\Controllers;

use \Core\View;

/**
 * Expense controller
 */
class Expense extends Authenticated
{
    /**
     * Show the balance page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Expense/addExpense.html', []);
    }
}
