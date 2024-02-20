<?php

namespace App\Models;

use PDO;

/**
 * Income model
 */
#[\AllowDynamicProperties]
class Income extends \Core\Model
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
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Saves the income with the current property values.
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return boolean True if the income was saved, false otherwise
     */
    public function save($user_id = 0)
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO incomes (user_id, value, date, category_id, comment)
            VALUES (:user_id, :value, :date, :category_id, :comment)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':value', $this->value, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);
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
        if ($this->value == '') {
            $this->errors[] = 'Wartość jest wymagana.';
        }
        if ($this->value < 0) {
            $this->errors[] = 'Wartość nie może być mniejsza od 0';
        }
        if ($this->date == '') {
            $this->errors[] = 'Data jest wymagana.';
        }
        if (!isset($this->category)) {
            $this->errors[] = 'Kategoria jest wymagana.';
        }
    }
}