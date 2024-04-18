<?php

namespace App\Models;

use PDO;

/**
 * Payment method model
 */
#[\AllowDynamicProperties]
class PaymentMethod extends \Core\Model
{
    /**
     * Error message
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
    public function __construct($user_id, $data = [])
    {
        $this->user_id = $user_id;
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Saves the payment method with the current property values.
     * 
     * @return boolean True if the payment method was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO payment_methods (user_id, method)
            VALUES (:user_id, :method)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':method', ucfirst($this->method), PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Changes the payment method with the current property values.
     * 
     * @return boolean True if the payment method was changed, false otherwise
     */
    public function change()
    {
        $this->validate($this->oldMethod ?? null);

        if (empty($this->errors)) {
            $sql = 'UPDATE payment_methods
            SET method = :method
            WHERE method_id = :method_id
            AND user_id = :user_id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':method', ucfirst($this->method), PDO::PARAM_STR);
            $stmt->bindValue(':method_id', $this->oldMethod, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    private function validate()
    {
        if ($this->method == '') {
            $this->errors[] = 'Nie wpisano nazwy metody.';
        }
        if (static::methodExists($this->user_id, $this->method)) {
            $this->errors[] = 'Taka metoda już istnieje.';
        }
        if (
            isset($this->oldMethod)
            && $this->oldMethod == ''
        ) {
            $this->errors[] = 'Nie wybrano metody płatności do zmiany.';
        }
    }

    /**
     * See if method already exists for the user
     * 
     * @param string $user_id userID to search for
     * @param string $method method to search for
     *
     * @return boolean  True if a record already exists with the specified userId and category, false otherwise
     */
    public static function methodExists($user_id, $method)
    {
        $methods = static::findByUserID($user_id);
        foreach ($methods as $element) {
            if (strtolower($element["method"]) == strtolower($method)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finds payment methods by userID
     * 
     * @param string $user_id userID to search for
     * 
     * @return mixed Array of payment methods of the user if found, false otherwise
     */
    public static function findByUserID($userID)
    {
        $sql = 'SELECT * FROM payment_methods 
        WHERE user_id = :user_id
        ORDER BY method';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}