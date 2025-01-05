<?php

namespace App\Models;

use PDO;

/**
 * Expense model
 */
#[\AllowDynamicProperties]
class Expense extends \Core\Model
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
     * Saves the expense with the current property values.
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return boolean True if the expense was saved, false otherwise
     */
    public function save($user_id = 0)
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO expenses (user_id, value, date, method_id, category_id, comment)
            VALUES (:user_id, :value, :date, :method_id, :category_id, :comment)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':value', $this->value, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':method_id', $this->method, PDO::PARAM_INT);
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
        if (!isset($this->method)) {
            $this->errors[] = 'Sposób płatności jest wymagany.';
        }
        if (!isset($this->category)) {
            $this->errors[] = 'Kategoria jest wymagana.';
        }
    }

    /**
     * Find all expenses by user ID
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return mixed  An array with all expernses of the user or null if there are no expenses
     */
    public static function findExpensesByUserID($user_id = 0)
    {
        $sql = 'SELECT expenses.expense_id, expenses.value, expenses.date, payment_categories.category, payment_methods.method, expenses.comment
        FROM expenses
        LEFT OUTER JOIN payment_categories
        ON expenses.category_id = payment_categories.category_id
        LEFT OUTER JOIN payment_methods
        ON expenses.method_id = payment_methods.method_id
        WHERE expenses.user_id = :user_id
        ORDER BY payment_categories.category, 
        expenses.date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find total value of expenses of each category by user ID
     * 
     * @param int $user_id  ID of logged in user
     * 
     * @return mixed  An array with total values (sums) of all expenses of the user for each category or null if there are no expenses
     */
    public static function findSumsOfEachCategoryByUserID($user_id = 0)
    {
        $sql = 'SELECT payment_categories.category, 
                sum(expenses.value) AS "sum"
                FROM expenses
                LEFT OUTER JOIN payment_categories
                ON expenses.category_id = payment_categories.category_id
                WHERE expenses.user_id = :user_id
                GROUP BY payment_categories.category
                ORDER BY sum DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find all expenses by user ID
     * 
     * @param int $user_id  ID of logged in user
     * @param string $start_date start date of wanted incomes
     * @param string $end_date end date of wanted incomes
     * 
     * @return mixed  An array with all expernses of the user or null if there are no expenses
     */
    public static function findExpensesByUserIDAndDate($user_id = 0, $start_date = '1990-01-01', $end_date = '1990-01-01')
    {
        $sql = 'SELECT expenses.expense_id, expenses.value, expenses.date, payment_categories.category, payment_methods.method, expenses.comment
        FROM expenses
        LEFT OUTER JOIN payment_categories
        ON expenses.category_id = payment_categories.category_id
        LEFT OUTER JOIN payment_methods
        ON expenses.method_id = payment_methods.method_id
        WHERE expenses.user_id = :user_id
        AND expenses.date BETWEEN :start_date AND :end_date
        ORDER BY payment_categories.category, 
        expenses.date DESC';

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
     * Find total value of expenses of each category by user ID, start date and end date
     * 
     * @param int $user_id  ID of logged in user
     * @param string $start_date start date of wanted incomes
     * @param string $end_date end date of wanted incomes
     * 
     * @return mixed  An array with total values (sums) of all expenses of the user for each category or null if there are no expenses
     */
    public static function findSumsOfEachCategoryByUserIDAndDate($user_id = 0, $start_date = '1990-01-01', $end_date = '1990-01-01')
    {
        $sql = 'SELECT payment_categories.category, 
                sum(expenses.value) AS "sum"
                FROM expenses
                LEFT OUTER JOIN payment_categories
                ON expenses.category_id = payment_categories.category_id
                WHERE expenses.user_id = :user_id
                AND expenses.date BETWEEN :start_date AND :end_date
                GROUP BY payment_categories.category
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

    public static function getSumOfExpensesByCategoryIDAndDate(
        $user_id,
        $category_id,
        $date
    ) {
        /*
        SELECT SUM(expenses.value) AS 'sum'
        FROM expenses
        WHERE expenses.user_id = 17 
        AND expenses.category_id = 154 
        AND date_format(expenses.date, '%Y-%m')=date_format('2024-11-25', '%Y-%m');
        */

        $sql = 'SELECT sum(expenses.value) AS "sum"
        FROM expenses
        WHERE expenses.user_id = :user_id
        AND expenses.category_id = :category_id
        AND date_format(expenses.date, "%Y-%m")=date_format(:date, "%Y-%m")';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetch();
    }
}