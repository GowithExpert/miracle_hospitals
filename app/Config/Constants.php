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
#Custom Defined Constants:
$userroles = array(0, 1, 2, 3, 4); //0: Frontdesk (& Donor), 1: Admin 2: Accountants Role, 3: Doctors, 4: Patients & Blood Bank

define('WEBSITE', 'https://neoarks.com');
define('ADMIN_EMAIL', 'mgt@neoarksoftware.com');
define('DEV_TEAM', 'Neoark Team');
define('DEV_AUTHOR', 'Miracle Hospitals');  //Need to replace with HOSPITAL_NAME
define('SLOGAN', 'NEORX'); //Rx means "you" in medical
define('HOSPITAL_NAME', 'Miracle Hospitals');
define('ISU_SUPPORT', 'Please contact admin for support ');
define('CONTACT_ADDRS', '305 Signature Tower, <br/> New Delhi - 110093, India (Asia)');
define('ADMIT_FEE', '5000.00'); //Admission Fee
define('APMT_REGIS_FEE', '100.00'); //Appointment Registration Fee
define('APMT_FEE_TAX', '0'); //Appointment Fee Tax %
define('ALLOW_MAX_UPLOAD', '500'); // Allow maximum upload sizes


define('CONTACT_AT', '+91 880 090 0164');
define('GGL_CLNT_ID', '597704537216-7qiivrukccjfm4i1ijjlm498hcvqnm12.apps.googleusercontent.com');  //Google Client ID - for Google sign-in
define('GGL_CLNT_SECRET', 'VjDBo2PSnA53l5ehfsRFSgMK');                                              //Google Client Secret - for Google sign-in

define('SMS_API_URL', 'https://api.textlocal.in/send/');  //Refer: app/Controllers/Blood_bank.php
define('SMS_API_CODE', 'TXTLCL');                         //Refer: app/Controllers/Blood_bank.php
define('SMS_API_KEY', 'WI3ARZJBQ7I-DUkCeXTniz6ZrPmKYi4FIFUKlbwkS0'); //Refer: app/Controllers/Blood_bank.php

define('FB_LINK', 'https://www.facebook.com/ne0arks'); //Refer: app/Controllers/Blood_bank.php
define('WHATSUP_LINK', ''); //Refer: app/Controllers/Blood_bank.php
define('INSTA_LINK', ''); //Refer: app/Controllers/Blood_bank.php
define('TWITTER_LINK', 'https://twitter.com/neoark_s'); //Refer: app/Controllers/Blood_bank.php

defined('APP_TIMEZONE') || define('APP_TIMEZONE', 'Asia/Kolkata');
defined('HRS_DIGITS') || define('HRS_DIGITS', '2-digit'); //Hour Time Digits
defined('MIN_DIGITS') || define('MIN_DIGITS', '2-digit'); //Minutes Time Digits
defined('LOCALE_TM_STR') || define('LOCALE_TM_STR', 'en-IN'); //Locale Time String


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
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
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
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

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
