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

    public static function findByUserID($userID = null)
    {
        $sql = 'SELECT * FROM income_categories WHERE user_id = :user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}