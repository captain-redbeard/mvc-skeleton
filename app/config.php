<?php
/**
 * Details:
 * This is the configuration file, be sure to change the values as required.
 *
 * @author captain-redbeard
 * @since 20/01/17
 */

//Database
getenv("DB_HOSTNAME") != null ? define("DB_HOSTNAME", getenv("DB_HOSTNAME")) : define("DB_HOSTNAME", "localhost");
getenv("DB_DATABASE") != null ? define("DB_DATABASE", getenv("DB_DATABASE")) : define("DB_DATABASE", "mvc-skeleton");
getenv("DB_USERNAME") != null ? define("DB_USERNAME", getenv("DB_USERNAME")) : define("DB_USERNAME", "");
getenv("DB_PASSWORD") != null ? define("DB_PASSWORD", getenv("DB_PASSWORD")) : define("DB_PASSWORD", "");
getenv("DB_CHARSET") != null ? define("DB_CHARSET", getenv("DB_CHARSET")) : define("DB_CHARSET", "utf8mb4");

//App
define("BASE_DIR", __DIR__);
define("SITE_NAME", "Redbeards MVC Skeleton");
define("TIMEZONE", "Australia/Brisbane");
define("USESSION", "redbeard_user");
define("PW_COST", 12);
define("SECURE", false);
define("APP_PATH", "\\Redbeard\\");
define("DEFAULT_CONTROLLER", "Home");
define("DEFAULT_METHOD", "index");
define("MAX_LOGIN_ATTEMPTS", 5);