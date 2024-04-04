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
        if (!is_null($_GET['period'] ?? null)) {
            $period = explode('|', $_GET['period']);
        } else {
            date_default_timezone_set('Europe/Warsaw');
            $period = ['thisMonth', date('Y-m') . '-01', date('Y-m-t'), 'bieżący miesiąc'];
        }

        $incomes = Income::findIncomesByUserIDAndDate($this->user->user_id, $period[1], $period[2]);
        $expenses = Expense::findExpensesByUserIDAndDate($this->user->user_id, $period[1], $period[2]);
        $totalIncomesPerCategories = Income::findSumsOfEachCategoryByUserIDAndDate($this->user->user_id, $period[1], $period[2]);
        $totalExpensesPerCategories = Expense::findSumsOfEachCategoryByUserIDAndDate($this->user->user_id, $period[1], $period[2]);
        View::renderTemplate(
            'Balance/balance.html',
            [
                'incomes' => $incomes,
                'expenses' => $expenses,
                'income_categories_totals' => $totalIncomesPerCategories,
                'expense_categories_totals' => $totalExpensesPerCategories,
                'period' => $period
            ]
        );
    }
}