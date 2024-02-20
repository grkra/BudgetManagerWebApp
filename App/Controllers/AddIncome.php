<?php
namespace App\Controllers;

use App\Flash;
use \Core\View;
use \App\Models\Income;
use App\Auth;

/**
 * Income controller
 */
class AddIncome extends Authenticated
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
     * Show the add income page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Income/addIncome.html', []);
    }

    /**
     * Add new income
     */
    public function createAction()
    {
        $income = new Income($_POST);
        if ($income->save($this->user->user_id)) {
            Flash::addMessage('Dodano przychód', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Income/addIncome.html', ['income' => $income]);
        }
    }

}
