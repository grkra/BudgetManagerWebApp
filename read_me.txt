---------------------------------------------
Install Composer and dependencies:
---------------------------------------------
	composer install

---------------------------------------------
Add file App\Config.php with credentials to connect to database
---------------------------------------------	

	<?php
	namespace App;
	/**
	 * Application configuration
	 */
	class Config
	{
		/**
		 * Database host
		 * @var string
		 */
		const DB_HOST = ...;
		/**
		 * Database name
		 * @var string
		 */
		const DB_NAME = ...;
		/**
		 * Database user
		 * @var string
		 */
		const DB_USER = ...;
		/**
		 * Database password
		 * @var string
		 */
		const DB_PASSWORD = ...;
		/**
		 * Show or hide error messages on screen
		 * @var boolean
		 */
		const SHOW_ERRORS = ...;
		/**
		 * Secret key for hashing
		 * @var boolean
		 */
		const SECRET_KEY = ...;
		/**
		 * PHPMailer host
		 *
		 * @var string
		 */
		const MAIL_HOST = ...;
		/**
		 * PHPMailer port
		 *
		 * @var int
		 */
		const MAIL_PORT = ...;
		/**
		 * PHPMailer username
		 *
		 * @var string
		 */
		const MAIL_USERNAME = ...;
		/**
		 * PHPMailer password
		 *
		 * @var string
		 */
		const MAIL_PASSWORD = ...;
		/**
		 * PHPMailer from email address
		 *
		 * @var string
		 */
		const MAIL_FROM_ADDRESS = ...;
		/**
		 * PHPMailer from email name
		 *
		 * @var string
		 */
		const MAIL_FROM_NAME = ...;
	}

---------------------------------------------	
Create database with structure:
---------------------------------------------	

	CREATE TABLE `expenses` (
	  `expense_id` int(10) UNSIGNED NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `value` double(10,2) UNSIGNED NOT NULL,
	  `date` date NOT NULL,
	  `method_id` int(10) UNSIGNED DEFAULT NULL,
	  `category_id` int(10) UNSIGNED NOT NULL,
	  `comment` varchar(100) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `incomes` (
	  `income_id` int(10) UNSIGNED NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `value` double(10,2) NOT NULL,
	  `date` date NOT NULL,
	  `category_id` int(10) UNSIGNED NOT NULL,
	  `comment` varchar(100) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `income_categories` (
	  `category_id` int(10) UNSIGNED NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `category` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `income_categories_default` (
	  `id` int(10) UNSIGNED NOT NULL,
	  `category` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	INSERT INTO `income_categories_default` (`id`, `category`) VALUES
	(1, 'Wynagrodzenie'),
	(2, 'Odsetki bankowe'),
	(3, 'Sprzedaż na allegro'),
	(4, 'Inny');

	CREATE TABLE `payment_categories` (
	  `category_id` int(10) UNSIGNED NOT NULL,
	  `category` varchar(50) NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `category_limit` double(10,2) DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `payment_categories_default` (
	  `id` int(10) UNSIGNED NOT NULL,
	  `category` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	INSERT INTO `payment_categories_default` (`id`, `category`) VALUES
	(4, 'Jedzenie'),
	(5, 'Mieszkanie'),
	(6, 'Transport'),
	(7, 'Telekomunikacja'),
	(8, 'Opieka zdrowotna'),
	(9, 'Ubranie'),
	(10, 'Higiena'),
	(11, 'Dzieci'),
	(12, 'Rozrywka'),
	(13, 'Wycieczka'),
	(14, 'Szkolenia'),
	(15, 'Książki'),
	(16, 'Oszczędności'),
	(17, 'Na złotą jesień, czyli emeryturę'),
	(18, 'Spłata długów'),
	(19, 'Darowizna'),
	(20, 'Inny');

	CREATE TABLE `payment_methods` (
	  `method_id` int(10) UNSIGNED NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `method` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `payment_methods_default` (
	  `id` int(10) UNSIGNED NOT NULL,
	  `method` varchar(50) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	INSERT INTO `payment_methods_default` (`id`, `method`) VALUES
	(1, 'Gotówka'),
	(2, 'Karta debetowa'),
	(3, 'Karta kredytowa');

	CREATE TABLE `remembered_logins` (
	  `token_hash` varchar(64) NOT NULL,
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `expiriation` datetime NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	CREATE TABLE `users` (
	  `user_id` int(10) UNSIGNED NOT NULL,
	  `name` varchar(50) NOT NULL,
	  `email` varchar(255) NOT NULL,
	  `password_hash` varchar(255) NOT NULL,
	  `activation_hash` varchar(64) DEFAULT NULL,
	  `is_active` tinyint(1) NOT NULL DEFAULT 0,
	  `password_reset_hash` varchar(64) DEFAULT NULL,
	  `password_reset_expires_at` datetime DEFAULT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

	ALTER TABLE `expenses`
	  ADD PRIMARY KEY (`expense_id`),
	  ADD KEY `user_id` (`user_id`),
	  ADD KEY `method_id` (`method_id`),
	  ADD KEY `category_id` (`category_id`);

	ALTER TABLE `incomes`
	  ADD PRIMARY KEY (`income_id`),
	  ADD KEY `user_id` (`user_id`),
	  ADD KEY `category_id` (`category_id`);

	ALTER TABLE `income_categories`
	  ADD PRIMARY KEY (`category_id`),
	  ADD KEY `user_id` (`user_id`),
	  ADD KEY `user_id_2` (`user_id`);

	ALTER TABLE `income_categories_default`
	  ADD PRIMARY KEY (`id`);

	ALTER TABLE `payment_categories`
	  ADD PRIMARY KEY (`category_id`),
	  ADD KEY `user_id` (`user_id`);

	ALTER TABLE `payment_categories_default`
	  ADD PRIMARY KEY (`id`);

	ALTER TABLE `payment_methods`
	  ADD PRIMARY KEY (`method_id`),
	  ADD KEY `user_id` (`user_id`);

	ALTER TABLE `payment_methods_default`
	  ADD PRIMARY KEY (`id`);

	ALTER TABLE `remembered_logins`
	  ADD PRIMARY KEY (`token_hash`),
	  ADD KEY `user_id` (`user_id`);

	ALTER TABLE `users`
	  ADD PRIMARY KEY (`user_id`),
	  ADD UNIQUE KEY `email` (`email`),
	  ADD UNIQUE KEY `activation_hash` (`activation_hash`);

	ALTER TABLE `expenses`
	  MODIFY `expense_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

	ALTER TABLE `incomes`
	  MODIFY `income_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

	ALTER TABLE `income_categories`
	  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

	ALTER TABLE `income_categories_default`
	  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

	ALTER TABLE `payment_categories`
	  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

	ALTER TABLE `payment_categories_default`
	  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

	ALTER TABLE `payment_methods`
	  MODIFY `method_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

	ALTER TABLE `payment_methods_default`
	  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

	ALTER TABLE `users`
	  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

	ALTER TABLE `expenses`
	  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`method_id`) REFERENCES `payment_methods` (`method_id`) ON DELETE SET NULL ON UPDATE SET NULL,
	  ADD CONSTRAINT `expenses_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `payment_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE `incomes`
	  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
	  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `income_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE `income_categories`
	  ADD CONSTRAINT `income_categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE `payment_categories`
	  ADD CONSTRAINT `payment_categories_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

	ALTER TABLE `payment_methods`
	  ADD CONSTRAINT `payment_methods_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
	COMMIT;