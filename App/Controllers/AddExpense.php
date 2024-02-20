<?php
namespace App\Controllers;

use App\Flash;
use \Core\View;
use \App\Models\Expense;
use \App\Models\PaymentCategory;
use \App\Models\PaymentMethod;
use App\Auth;

/**
 * Expense controller
 */
class AddExpense extends Authenticated
{
    /**
     * Before filter - called before each action method
     * 
     * @return void
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
    }

    /**
     * Show the add expense page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Expense/addExpense.html', []);
    }

    /**
     * Add new expense
     */
    public function createAction()
    {
        $expense = new Expense($_POST);
        if ($expense->save($this->user->user_id)) {
            Flash::addMessage('Dodano wydatek', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Expense/addExpense.html', ['expense' => $expense]);
        }
    }
}
