<?php

namespace App\Models;

use PDO;

/**
 * Payment method model
 * 
 * @param string $userId The user ID
 * 
 * @return mixed  An array with all the payment methods of the user or null if there are no categories
 */
#[\AllowDynamicProperties]
class PaymentMethod extends \Core\Model
{

    public static function findByUserID($userID)
    {
        $sql = 'SELECT * FROM payment_methods WHERE user_id = :user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}