<?php

/**
 * Front controller
 */

/**
 * Composer
 */
require '../vendor/autoload.php';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Session
 */
session_start();

/**
 * Routing
 */
$router = new Core\Router();

/** Add routs */
/**
 * API endpoint to get limit of specified expense category
 */
$router->add('api/limit/{category:[\d]+}', ['controller' => 'AddExpense', 'action' => 'limit']);

/**
 * API endpoint to get sum of expenses for specified expense category and date
 */
$router->add('api/expenses/{category:[\d]+}/{date:[\d][\d][\d][\d]-[\d][\d]-[\d][\d]}', ['controller' => 'AddExpense', 'action' => 'expensesCategoryMonth']);

/**
 * API endpoint to set expense category limit
 */
$router->add('api/set-limit', ['controller' => 'Properties', 'action' => 'setLimit']);

/**
 * API endpoint to delete expense category limit
 */
$router->add('api/delete-limit', ['controller' => 'Properties', 'action' => 'deleteLimit']);

$router->add('', ['controller' => 'Home', 'action' => 'new']);
$router->add('add-income', ['controller' => 'AddIncome', 'action' => 'new']);
$router->add('add-expense', ['controller' => 'AddExpense', 'action' => 'new']);
$router->add('balance', ['controller' => 'Balance', 'action' => 'new']);
$router->add('properties', ['controller' => 'Properties', 'action' => 'new']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('register', ['controller' => 'Signup', 'action' => 'new']);

$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('signup/activate/{token:[\da-f]+}', ['controller' => 'Signup', 'action' => 'activate']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

$router->dispatch($_SERVER['QUERY_STRING']);
