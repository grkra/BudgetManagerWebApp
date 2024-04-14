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
    public function save($user_id)
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
        if ($this->value <= 0) {
            $this->errors[] = 'Wartość musi być większa od 0';
        }
        if ($this->date == '') {
            $this->errors[] = 'Data jest wymagana.';
        }
        if (!isset($this->category)) {
            $this->errors[] = 'Kategoria jest wymagana.';
        }
    }

    /**
     * Find all incomes by user ID
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return mixed  An array with all incomes of the user or null if there are no incomes
     */
    public static function findIncomesByUserID($user_id = 0)
    {
        $sql = 'SELECT incomes.income_id, incomes.value, incomes.date, income_categories.category, incomes.comment
        FROM incomes
        INNER JOIN income_categories
        ON incomes.category_id = income_categories.category_id
        WHERE incomes.user_id = :user_id
        ORDER BY income_categories.category, 
        incomes.date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find total value of incomes of each category by user ID
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return mixed  An array with total values (sums) of all incomes of the user for each category or null if there are no incomes
     */
    public static function findSumsOfEachCategoryByUserID($user_id = 0)
    {
        $sql = 'SELECT income_categories.category, 
                sum(incomes.value) AS "sum"
                FROM incomes
                LEFT OUTER JOIN income_categories
                ON incomes.category_id = income_categories.category_id
                WHERE incomes.user_id = :user_id
                GROUP BY income_categories.category
                ORDER BY sum DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find incomes by user ID, start date and end date
     * 
     * @param int $user_id ID of logged in user
     * @param string $start_date start date of wanted incomes
     * @param string $end_date end date of wanted incomes
     * 
     * @return mixed  An array with all incomes of the user from the period or null if there are no incomes
     */
    public static function findIncomesByUserIDAndDate($user_id = 0, $start_date = '1990-01-01', $end_date = '1990-01-01')
    {
        $sql = 'SELECT incomes.income_id, incomes.value, incomes.date, income_categories.category, incomes.comment
        FROM incomes
        INNER JOIN income_categories
        ON incomes.category_id = income_categories.category_id
        WHERE incomes.user_id = :user_id
        AND incomes.date BETWEEN :start_date AND :end_date
        ORDER BY income_categories.category, 
        incomes.date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find total value of incomes of each category by user ID, start date and end date
     * 
     * @param int $user_id ID of logged in user
     * @param string $start_date start date of wanted incomes
     * @param string $end_date end date of wanted incomes
     * 
     * @return mixed  An array with total values (sums) of all incomes of the user for each category or null if there are no incomes
     */
    public static function findSumsOfEachCategoryByUserIDAndDate($user_id = 0, $start_date = '1990-01-01', $end_date = '1990-01-01')
    {
        $sql = 'SELECT income_categories.category, 
                sum(incomes.value) AS "sum"
                FROM incomes
                LEFT OUTER JOIN income_categories
                ON incomes.category_id = income_categories.category_id
                WHERE incomes.user_id = :user_id
                AND incomes.date BETWEEN :start_date AND :end_date
                GROUP BY income_categories.category
                ORDER BY sum DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}