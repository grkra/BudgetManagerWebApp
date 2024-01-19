Install Composer and dependencies:
	composer install

Add App\Config.php:
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