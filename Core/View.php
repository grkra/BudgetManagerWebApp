<?php

namespace Core;

/**
 * View
 */
class View
{

    /**
     * Render a view file
     * 
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);
        $file = "../App/Views/$view";  // relative to Core directory
        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     * 
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        echo static::getTemplate($template, $args);

        // $data = \App\Models\PaymentCategory::findByUserID(\App\Auth::getUser()->user_id ?? null);

        // foreach ($data as $key => $value) {
        //     echo ($key . '<br>');
        //     foreach ($value as $a => $b) {
        //         echo ('   ' . $a . ': ' . $b . '<br>');
        //     }

        // }

        /*
0
category_id: 163
category: Darowizna
user_id: 17
category_limit:
1
category_id: 155
category: Dzieci
user_id: 17
category_limit:
2
category_id: 154
category: Higiena
user_id: 17
category_limit:
3
category_id: 164
category: Inny
user_id: 17
category_limit:
4
category_id: 148
category: Jedzenie
user_id: 17
category_limit:
5
category_id: 159
category: Książki
user_id: 17
category_limit:
6
category_id: 149
category: Mieszkanie
user_id: 17
category_limit:
7
category_id: 161
category: Na złotą jesień, czyli emeryturę
user_id: 17
category_limit:
8
category_id: 152
category: Opieka zdrowotna
user_id: 17
category_limit:
9
category_id: 160
category: Oszczędności
user_id: 17
category_limit:
10
category_id: 156
category: Rozrywka
user_id: 17
category_limit:
11
category_id: 162
category: Spłata długów
user_id: 17
category_limit:
12
category_id: 158
category: Szkolenia
user_id: 17
category_limit:
13
category_id: 151
category: Telekomunikacja
user_id: 17
category_limit:
14
category_id: 150
category: Transport
user_id: 17
category_limit: 200.00
15
category_id: 153
category: Ubranie
user_id: 17
category_limit:
16
category_id: 157
category: Wycieczka
user_id: 17
category_limit:

        */
    }

    /**
     * Get the contents of a view template using Twig
     * 
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return string
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;
        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);

            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            $twig->addGlobal('income_categories', \App\Models\IncomeCategory::findByUserID(\App\Auth::getUser()->user_id ?? null));
            $twig->addGlobal('payment_methods', \App\Models\PaymentMethod::findByUserID(\App\Auth::getUser()->user_id ?? null));
            $twig->addGlobal('payment_categories', \App\Models\PaymentCategory::findByUserID(\App\Auth::getUser()->user_id ?? null));
        }

        return $twig->render($template, $args);
    }
}
