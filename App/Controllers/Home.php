<?php
namespace App\Controllers;

use App\Auth;
use App\Models\Expense;
use App\Models\Income;
use \Core\View;

/**
 * Home controller
 */
class Home extends Authenticated
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
     * Show the index page
     * @return void
     */
    public function newAction()
    {
        date_default_timezone_set('Europe/Warsaw');
        $period = ['thisMonth', date('Y-m') . '-01', date('Y-m-t'), 'bieżący miesiąc'];

        $totalIncomesPerCategories = Income::findSumsOfEachCategoryByUserIDAndDate($this->user->user_id, $period[1], $period[2]);
        $totalExpensesPerCategories = Expense::findSumsOfEachCategoryByUserIDAndDate($this->user->user_id, $period[1], $period[2]);

        View::renderTemplate('Home/home.html', [
            'income_categories_totals' => $totalIncomesPerCategories,
            'expense_categories_totals' => $totalExpensesPerCategories
        ]);
    }
}
