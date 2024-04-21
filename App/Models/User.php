<?php

namespace App\Models;

use App\Mail;
use App\Token;
use Core\View;
use PDO;


/**
 * User model
 */
#[\AllowDynamicProperties]
class User extends \Core\Model
{

    /**
     * Error messages
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     * 
     * @param array $data  Initial property values
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Save the user model with the current property values     
     * 
     * @return boolean True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users (name, email, password_hash, activation_hash) VALUES (:name, :email, :password_hash, :activation_hash)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':activation_hash', $hashed_token, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Update the user's profile
     * 
     * @param array $data Data from the edit profile form
     *
     * @return boolean  True if the data was updated, false otherwise
     */
    public function change($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        if ($data['password'] != '') {
            $this->password = $data['password'];
        }

        $this->validate();

        if (empty($this->errors)) {
            $sql = 'UPDATE users
                    SET name = :name,
                        email = :email';

            if (isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= "\nWHERE user_id = :user_id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);

            if (isset($this->password)) {
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }

            return $stmt->execute();
        }
        return false;
    }

    /**
     * Deletes user from database     
     * 
     * @return boolean True if the user was deleted, false otherwise
     */
    public function delete($confirmation)
    {
        if (!$confirmation) {
            $this->errors[] = 'Wymagane potwierdzenie.';
        }

        if (empty($this->errors)) {
            $sql = 'DELETE FROM users
            WHERE user_id = :user_id
            AND name = :name
            AND email = :email';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     * 
     * @return void
     */
    public function validate()
    {
        // name
        if ($this->name == '') {
            $this->errors[] = 'Imię jest wymagane.';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Błędny adres email.';
        }
        if (static::emailExists($this->email, $this->user_id ?? null)) {
            $this->errors[] = 'Istnieje już konto dla podanego adresu email.';
        }

        // Password
        if (isset($this->password)) {
            if (strlen($this->password) < 6) {
                $this->errors[] = 'Hasło musi mieć min. 6 znaków.';
            }
            if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
                $this->errors[] = 'Hasło musi miec min. 1 literę.';
            }
            if (preg_match('/.*\d+.*/i', $this->password) == 0) {
                $this->errors[] = 'Hasło musi miec min. 1 cyfrę.';
            }
        }
    }

    /**
     * See if a user record already exists with the specified email
     * 
     * @param string $email email address to search for
     * @param string $ignore_id Return false anyway if the record found has this ID
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);
        if ($user) {
            if ($user->user_id != $ignore_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Find a user model by email address
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Send an email to the user containing the activation link
     * @return void
     */
    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;
        $text = View::getTemplate('Signup/activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup/activation_email.html', ['url' => $url]);
        Mail::send($this->email, 'Account activation', $text, $html);
    }

    /**
     * Authenticate a user by email and password.
     * 
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {

        $user = static::findByEmail($email);
        if ($user && $user->is_active) {
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }
        return false;
    }

    /**
     * Find a user model by ID
     * 
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByID($user_id)
    {
        $sql = 'SELECT * FROM users WHERE user_id = :user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table for this user record
     * 
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 * 24 * 7;  // 7 days from now
        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expiriation)
                VALUES (:token_hash, :user_id, :expiriation)';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
        $stmt->bindValue(':expiriation', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Activate the user account with the specified activation token
     * 
     * @param string $value Activation token from the URL
     *
     * @return void
     */
    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();
        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null
                WHERE activation_hash = :hashed_token';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Add default income categories, payment categories and payment methods by copying from income_categories_default, payment_categories_default, payment_methods_default tables
     * @return void
     */
    public function addDefaultIncomePaymentCategories()
    {
        $sql = 'INSERT INTO income_categories (category, user_id)
                SELECT income_categories_default.category, users.user_id
                FROM income_categories_default, users
                WHERE users.email = :email;

                INSERT INTO payment_categories (category, user_id)
                SELECT payment_categories_default.category, users.user_id
                FROM payment_categories_default, users
                WHERE users.email = :email;

                INSERT INTO payment_methods (method, user_id)
                SELECT payment_methods_default.method, users.user_id
                FROM payment_methods_default, users
                WHERE users.email = :email;';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
        $stmt->execute();
    }
}
