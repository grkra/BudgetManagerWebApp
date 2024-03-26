<?php
namespace App\Controllers;

use App\Auth;
use App\Models\Expense;
use App\Models\Income;
use \Core\View;

/**
 * Home controller
 */
class Balance extends Authenticated
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
     * Show the balance page
     * @return void
     */
    public function newAction()
    {
        $incomes = Income::findIncomesByUserID($this->user->user_id);
        $expenses = Expense::findExpensesByUserID($this->user->user_id);
        $totalIncomesPerCategories = Income::findSumsOfEachCategoryByUserID($this->user->user_id);
        $totalExpensesPerCategories = Expense::findSumsOfEachCategoryByUserID($this->user->user_id);

        View::renderTemplate(
            'Balance/balance.html',
            [
                'incomes' => $incomes,
                'expenses' => $expenses,
                'income_categories_totals' => $totalIncomesPerCategories,
                'expense_categories_totals' => $totalExpensesPerCategories
            ]
        );
    }
}