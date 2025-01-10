<?php
namespace App\Controllers;

use App\Flash;
use App\Models\PaymentCategory;
use \Core\View;
use \App\Models\Expense;
use App\Auth;

/**
 * Expense controller
 */
class AddExpense extends Authenticated
{
    /**
     * Before filter - called before each action method
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
     * @return void
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

    /**
     * Returns limit for category passed as route parameter
     * @return void
     */
    public function limitAction()
    {
        $user_id = $this->user->user_id;
        $category_id = $this->route_params['category'];

        echo json_encode(PaymentCategory::getLimit($user_id, $category_id), JSON_UNESCAPED_UNICODE);
    }

    /**
     * Returns expenses for category and date passed as route parameters
     * @return void
     */
    public function expensesCategoryMonthAction()
    {
        $user_id = $this->user->user_id;
        $category_id = $this->route_params['category'];
        $date = $this->route_params['date'];

        echo json_encode(Expense::getSumOfExpensesByCategoryIDAndDate($user_id, $category_id, $date), JSON_UNESCAPED_UNICODE);
    }
}
