<?php

namespace App\Models;

use PDO;

/**
 * Income category model
 * 
 * @param string $userId The user ID
 * 
 * @return mixed  An array with all the income categories of the user or null if there are no categories
 */
#[\AllowDynamicProperties]
class IncomeCategory extends \Core\Model
{
    /**
     * Error message
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     * 
     * @param array $user_id  
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
     * Saves the income category with the current property values.
     * 
     * @return boolean True if the income category was saved, false otherwise
     */
    public function save()
    {
        $this->validate($this->oldCategory ?? null);

        if (empty($this->errors)) {
            $sql = 'INSERT INTO income_categories (user_id, category)
            VALUES (:user_id, :category)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':category', ucfirst($this->category), PDO::PARAM_STR);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Changes the income category with the current property values.
     * 
     * @return boolean True if the income category was changed, false otherwise
     */
    public function change()
    {
        $this->validate($this->oldCategory ?? null);

        if (empty($this->errors)) {
            $sql = 'UPDATE income_categories
            SET category = :category
            WHERE category_id = :category_id
            AND user_id = :user_id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':category', ucfirst($this->category), PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->oldCategory, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Deletes income category.
     * 
     * @param string $oldCategory id of category being removed
     * 
     * @return boolean True if the income category was deleted, false otherwise
     */
    public function delete($oldCategory)
    {
        $this->oldCategory = $oldCategory;

        $this->validate();

        if (empty($this->errors)) {
            $sql = 'DELETE FROM income_categories
            WHERE category_id = :category_id
            AND user_id = :user_id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':category_id', $this->oldCategory, PDO::PARAM_INT);
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
        if (
            isset($this->category) &&
            $this->category == ''
        ) {
            $this->errors[] = 'Nie wpisano nazwy kategorii.';
        }
        if (
            isset($this->category) &&
            static::categoryExists($this->user_id, $this->category)
        ) {
            $this->errors[] = 'Taka kategoria już istnieje.';
        }
        if (
            isset($this->oldCategory)
            && $this->oldCategory == ''
        ) {
            $this->errors[] = 'Nie wybrano kategorii.';
        }
    }

    /**
     * See if category already exists for the user
     * 
     * @param string $user_id userID to search for
     * @param string $category category to search for
     *
     * @return boolean  True if a record already exists with the specified userId and category, false otherwise
     */
    public static function categoryExists($user_id, $category)
    {
        $categories = static::findByUserID($user_id);
        foreach ($categories as $element) {
            if (strtolower($element["category"]) == strtolower($category)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finds income categories by userID
     * 
     * @param string $user_id userID to search for
     * 
     * @return mixed Array of income categories of the user if found, false otherwise
     */
    public static function findByUserID($user_id)
    {
        $sql = 'SELECT * FROM income_categories 
        WHERE user_id = :user_id
        ORDER BY category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}