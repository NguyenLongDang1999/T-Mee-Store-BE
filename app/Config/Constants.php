<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR') || define('HOUR', 3600);
defined('DAY') || define('DAY', 86400);
defined('WEEK') || define('WEEK', 604800);
defined('MONTH') || define('MONTH', 2_592_000);
defined('YEAR') || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS') || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR') || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG') || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE') || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS') || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE') || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN') || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);

/**
 * Status
 */
defined('STATUS_ACTIVE') || define('STATUS_ACTIVE', 1);
defined('STATUS_INACTIVE') || define('STATUS_INACTIVE', 0);

/**
 * Featured
 */
defined('FEATURED_ACTIVE') || define('FEATURED_ACTIVE', 1);
defined('FEATURED_INACTIVE') || define('FEATURED_INACTIVE', 0);

/**
 * Path URL Image
 */
defined('PATH_IMAGE_DEFAULT') || define('PATH_IMAGE_DEFAULT', 'assets/img/default.webp');
defined('PATH_CATEGORY_IMAGE') || define('PATH_CATEGORY_IMAGE', 'uploads/category/');
defined('PATH_BRAND_IMAGE') || define('PATH_BRAND_IMAGE', 'uploads/brand/');
defined('PATH_SLIDER_IMAGE') || define('PATH_SLIDER_IMAGE', 'uploads/slider/');
defined('PATH_USER_IMAGE') || define('PATH_USER_IMAGE', 'uploads/users/');
defined('PATH_PRODUCT_IMAGE') || define('PATH_PRODUCT_IMAGE', 'uploads/product/');

/**
 * Gender
 */
defined('GENDER_MALE') || define('GENDER_MALE', 1);
defined('GENDER_FEMALE') || define('GENDER_FEMALE', 0);

/**
 * Path CMS
 */
defined('PATH_CMS_ADMIN') || define('PATH_CMS_ADMIN', 'cms-admin');

/**
 * Message
 */
defined('MESSAGE_ERROR') || define('MESSAGE_ERROR', 'C?? l???i x???y ra trong qu?? tr??nh thao t??c. Vui l??ng ki???m tra l???i.');

/**
 * DateTime
 */
defined('FORMAT_DATE') || define('FORMAT_DATE', 'd-m-Y');

/**
 * Auth Login Status
 */
defined('AUTH_LOGIN_SUCCESS') || define('AUTH_LOGIN_SUCCESS', 1);
defined('AUTH_LOGIN_ERROR') || define('AUTH_LOGIN_ERROR', 0);

/**
 * Name Modules
 */
defined('MODULE_CATEGORY') || define('MODULE_CATEGORY', 'category');
defined('MODULE_PRODUCT') || define('MODULE_PRODUCT', 'product');
defined('MODULE_SLIDER') || define('MODULE_SLIDER', 'slider');
defined('MODULE_BRAND') || define('MODULE_BRAND', 'brand');
defined('MODULE_USER') || define('MODULE_USER', 'user');

/**
 * Type Discount
 */
defined('TYPE_DISCOUNT_VND') || define('TYPE_DISCOUNT_VND', 0);
defined('TYPE_DISCOUNT_PERCENT') || define('TYPE_DISCOUNT_PERCENT', 1);
