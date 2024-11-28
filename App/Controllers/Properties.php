<?php
namespace App\Controllers;

use App\Flash;
use App\Models\IncomeCategory;
use App\Models\PaymentCategory;
use App\Models\PaymentMethod;
use Core\View;
use App\Auth;
use App\Controllers\Login;

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
        $addedIncomeCategory = new IncomeCategory($this->user->user_id, $_POST);

        if ($addedIncomeCategory->save()) {
            Flash::addMessage('Dodano kategorię przychodów', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['addedIncomeCategory' => $addedIncomeCategory]);
        }
    }

    /**
     * Changes existing income category
     * @return void
     */
    public function changeIncomeCategoryAction()
    {
        $changedIncomeCategory = new IncomeCategory($this->user->user_id, $_POST);

        if ($changedIncomeCategory->change()) {
            Flash::addMessage('Zmieniono kategorię przychodów', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['changedIncomeCategory' => $changedIncomeCategory]);
        }
    }

    /**
     * Deletes existing income category
     * @return void
     */
    public function deleteIncomeCategoryAction()
    {
        $deletedIncomeCategory = new IncomeCategory($this->user->user_id);

        if ($deletedIncomeCategory->delete($_POST["oldCategory"])) {
            Flash::addMessage('Usunięto kategorię przychodów', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['deletedIncomeCategory' => $deletedIncomeCategory]);
        }
    }

    /**
     * Creates new expense category
     * @return void
     */
    public function addExpenseCategoryAction()
    {
        // category: 123
        // setLimit: on
        // limit: 123

        /*
        errors
user_id
category
setLimit
limit
        */

        $addedPaymentCategory = new PaymentCategory($this->user->user_id, $_POST);

        if ($addedPaymentCategory->save()) {
            Flash::addMessage('Dodano kategorię wydatków', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['addedExpenseCategory' => $addedPaymentCategory]);
        }
    }

    /**
     * Changes existing expense category
     * @return void
     */
    public function changeExpenseCategoryAction()
    {
        $changedPaymentCategory = new PaymentCategory($this->user->user_id, $_POST);

        if ($changedPaymentCategory->change()) {
            Flash::addMessage('Zmieniono kategorię wydatków', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['changedExpenseCategory' => $changedPaymentCategory]);
        }
    }

    /**
     * Deletes existing expense category
     * @return void
     */
    public function deleteExpenseCategoryAction()
    {
        $deletedExpenseCategory = new PaymentCategory($this->user->user_id);

        if ($deletedExpenseCategory->delete($_POST["oldCategory"])) {
            Flash::addMessage('Usunięto kategorię wydatków', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['deletedExpenseCategory' => $deletedExpenseCategory]);
        }
    }

    /**
     * Creates new payment method
     * @return void
     */
    public function addPaymentMethodAction()
    {
        $addedPaymentMethod = new PaymentMethod($this->user->user_id, $_POST);

        if ($addedPaymentMethod->save()) {
            Flash::addMessage('Dodano metodę płatności', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['addedPaymentMethod' => $addedPaymentMethod]);
        }
    }

    /**
     * Changes existing payment method
     * @return void
     */
    public function changePaymentMethodAction()
    {
        $changedPaymentMethod = new PaymentMethod($this->user->user_id, $_POST);

        if ($changedPaymentMethod->change()) {
            Flash::addMessage('Zmieniono metodę płatności', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['changedPaymentMethod' => $changedPaymentMethod]);
        }
    }

    /**
     * Deletes existing payment method
     * @return void
     */
    public function deletePaymentMethodAction()
    {
        $deletedPaymentMethod = new PaymentMethod($this->user->user_id);

        if ($deletedPaymentMethod->delete($_POST["oldMethod"])) {
            Flash::addMessage('Usunięto metodę płatności', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['deletedPaymentMethod' => $deletedPaymentMethod]);
        }
    }

    /**
     * Changes users data
     * @return void
     */
    public function changeUserDataAction()
    {
        if ($this->user->change($_POST)) {
            Flash::addMessage('Zmieniono dane użytkownika', 'success');
            $this->redirect('/');
        } else {
            View::renderTemplate('Properties/properties.html', ['changedUserData' => $this->user]);
        }
    }

    /**
     * Deletes user
     * @return void
     */
    public function deleteUserAction()
    {
        if ($this->user->delete(isset($_POST["confirm"]))) {
            Auth::logout();
            $this->redirect('/login/show-deleted-user-message');
        } else {
            View::renderTemplate('Properties/properties.html', ['deletedUser' => $this->user]);
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

    /**
     * Validates if payment method is available (AJAX).
     * 
     * @return void
     */
    public function validatePaymentMethodAction()
    {
        $is_valid = !PaymentMethod::methodExists($this->user->user_id, $_GET['method']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}