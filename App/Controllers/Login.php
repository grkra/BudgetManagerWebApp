<?php

namespace App\Controllers;

use App\Auth;
use Core\View;
use App\Flash;
use App\Models\User;

/**
 * Login controller
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    /**
     * Log in a user
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {
            Auth::login($user, $remember_me);
            $this->redirect(Auth::getReturnToPage());
        } else {
            Flash::addMessage('Błędny adres e-mali lub hasło');
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    /**
     * Log out a user
     * 
     * @return void
     */
    public function destroyAction()
    {
        Auth::logout();
        $this->redirect('/');
    }

    /**
     * Show a "deleted user" flash message and redirect to the homepage. Necessary to use the flash messages as they use the session and at the end of the logout method (destroyAction) the session is destroyed so a new action needs to be called in order to use the session.
     * 
     * @return void
     */
    public function showDeletedUserMessageAction()
    {
        Flash::addMessage('Usunięto konto', 'success');
        $this->redirect('/');
    }
}
