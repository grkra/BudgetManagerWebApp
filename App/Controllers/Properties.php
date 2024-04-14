<?php
namespace App\Controllers;

use App\Flash;
use App\Models\IncomeCategory;
use App\Models\PaymentCategory;
use Core\View;
use App\Auth;

/**
 * Properties controller
 */
class Properties extends Authenticated
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
        View::renderTemplate('Properties/properties.html', []);
    }

    /**
     * Creates new income category
     * @return void
     */
    public function addIncomeCategoryAction()
    {
        $incomeCategory = new IncomeCategory($this->user->user_id, $_POST);

        if ($incomeCategory->save()) {
            Flash::addMessage('Dodano kategorię przychodów', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['incomeCategory' => $incomeCategory]);
        }
    }

    /**
     * Creates new expense category
     * @return void
     */
    public function addExpenseCategoryAction()
    {
        $paymentCategory = new PaymentCategory($this->user->user_id, $_POST);

        if ($paymentCategory->save()) {
            Flash::addMessage('Dodano kategorię wydatków', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['expenseCategory' => $paymentCategory]);
        }
    }

    /**
     * Validates if income category is available (AJAX).
     * 
     * @return void
     */
    public function validateIncomeCategoryAction()
    {
        $is_valid = !IncomeCategory::categoryExists($this->user->user_id, $_GET['category']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }

    /**
     * Validates if expense category is available (AJAX).
     * 
     * @return void
     */
    public function validateExpenseCategoryAction()
    {
        $is_valid = !PaymentCategory::categoryExists($this->user->user_id, $_GET['category']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}