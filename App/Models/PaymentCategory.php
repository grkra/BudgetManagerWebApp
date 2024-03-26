<?php

namespace App\Models;

use PDO;

/**
 * Payment category model
 * 
 * @param string $userId The user ID
 * 
 * @return mixed  An array with all the payment categories of the user or null if there are no categories
 */
#[\AllowDynamicProperties]
class PaymentCategory extends \Core\Model
{

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
}