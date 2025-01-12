<?php

namespace App\Models;

use PDO;

/**
 * Payment category model
 */
#[\AllowDynamicProperties]
class PaymentCategory extends \Core\Model
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
     * Saves the payment category with the current property values.
     * 
     * @return boolean True if the payment category was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'INSERT INTO payment_categories (user_id, category';

            if (isset($this->setLimit)) {
                $sql .= ', category_limit';
            }

            $sql .= ')
            VALUES (:user_id, :category';

            if (isset($this->setLimit)) {
                $sql .= ', :category_limit';
            }

            $sql .= ')';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':category', ucfirst($this->category), PDO::PARAM_STR);

            if (isset($this->setLimit)) {
                $stmt->bindValue(':category_limit', $this->limit ?? null, PDO::PARAM_STR);
            }

            return $stmt->execute();
        }
        return false;
    }

    /**
     * Changes the expense category with the current property values.
     * 
     * @return boolean True if the expense category was changed, false otherwise
     */
    public function change()
    {
        $this->validate($this->oldCategory ?? null);

        if (empty($this->errors)) {
            $sql = 'UPDATE payment_categories
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
     * Deletes expense category.
     * 
     * @param string $oldCategory id of category being removed
     * 
     * @return boolean True if the expense category was deleted, false otherwise
     */
    public function delete($oldCategory)
    {
        $this->oldCategory = $oldCategory;

        $this->validate();

        if (empty($this->errors)) {
            $sql = 'DELETE FROM payment_categories
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
        if (
            isset($this->limit)
            && $this->limit == ''
        ) {
            $this->errors[] = 'Nie wpisano limitu.';
        }
        if (
            isset($this->limit)
            && $this->limit < 0
        ) {
            $this->errors[] = 'Limit musi być większy od 0.';
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
     * Finds expense categories by userID
     * 
     * @param string $user_id userID to search for
     * 
     * @return mixed Array of expense categories of the user if found, false otherwise
     */
    public static function findByUserID($userID)
    {
        $sql = 'SELECT * FROM payment_categories 
        WHERE user_id = :user_id
        ORDER BY category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Sets new limit for the expense category with the current property values.
     * 
     * @return boolean True if the limit for the expense category was set, false otherwise
     */
    public function setLimit()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'UPDATE payment_categories
            SET category_limit = :category_limit
            WHERE category_id = :category_id
            AND user_id = :user_id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':category_limit', $this->limit, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->oldCategory, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            return $stmt->execute();
        }
        return false;
    }

    /**
     * Returns limit of category
     * @param $user_id
     * @param $category_id
     * @return mixed Category limit if set in database, null if there was no limit
     */
    public static function getLimit($user_id, $category_id)
    {
        $sql = 'SELECT category_limit FROM payment_categories
        WHERE user_id = :user_id
        AND category_id = :category_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Deletes limit from category
     * @return boolean True if the limit for the expense category was deleted, false otherwise
     */
    public function deleteLimit()
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = 'UPDATE payment_categories
            SET category_limit = NULL
            WHERE category_id = :category_id
            AND user_id = :user_id';
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':category_id', $this->oldCategory, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 0) {
                    $this->errors[] = 'Wybrana kategoria jest niewłaściwa.';
                } else {
                    return true;
                }
            }
        }
        return false;
    }
}