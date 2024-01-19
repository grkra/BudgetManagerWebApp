<?php
namespace App\Controllers;

use \Core\View;

/**
 * Properties controller
 */
class Properties extends Authenticated
{
    /**
     * Show the balance page
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Properties/properties.html', []);
    }
}
