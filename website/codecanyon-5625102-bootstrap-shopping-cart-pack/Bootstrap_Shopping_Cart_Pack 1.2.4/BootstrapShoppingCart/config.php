<?php
//	
//	Aenea - www.beaenea.com
//	Bootstrap Shop Cart for codecanyon.net
//
require_once "eden.php";  // Eden Framework class mysql
require_once "PHPMailer-master/class.phpmailer.php"; //library phpmailer

$session = eden('session')->start();     //instantiate session
date_default_timezone_set('Europe/Paris');

//--------------------------------------------------------
// Currency setup 
//--------------------------------------------------------
define("EW_CURRENCY_SYMBOL", '$', TRUE); // Configure the symbol
define("EW_CURRENCY_CODE", 'USD', TRUE ); // USD or EUR or CAD or GBP or see all codes https://developer.paypal.com/webapps/developer/docs/classic/api/currency_codes/

//--------------------------------------------------------
// Mysql setup 
//--------------------------------------------------------

if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1" || $_SERVER["REMOTE_ADDR"] == "::1") { // local server
define("EW_CONN_HOST", '127.0.0.1', TRUE); // mysql host
define("EW_CONN_PORT", 3306, TRUE); // mysql port
define("EW_CONN_USER", 'root', TRUE); // mysql username
define("EW_CONN_PASS", '', TRUE); // mysql password
define("EW_CONN_DB", 'Bootstrapshopcart', TRUE); // mysql data base name
} else {
define("EW_CONN_HOST", '', TRUE); // mysql host
define("EW_CONN_PORT", 3306, TRUE); // mysql port
define("EW_CONN_USER", '', TRUE); // mysql username
define("EW_CONN_PASS", '', TRUE); // mysql password
define("EW_CONN_DB", '', TRUE); // mysql data base name
}



//--------------------------------------------------------
// Paypal setup 
//--------------------------------------------------------
$use_paypal_sandbox = FALSE; // false are real environment / true uses paypal sandbox 
define("EW_WEB_PATH", 'http://www.configureyourdomain.com/', TRUE); // Full script location URL  / its very important for put the final '/'  like http://www.mydom.com/ 
define("EW_PAYPAL_REGISTERED_EMAIL", 'configureyour@email.com', TRUE);  // Business email of store owner.
define("EW_EMAIL", 'configureyour@email.com', TRUE);// Email for news purchases

//--------------------------------------------------------
// Cash on delivery 
//--------------------------------------------------------
define("EW_CASHONDELIVERY", 10, TRUE); // Extra charge ? for Cash on delivery, no extra charge then 0

//--------------------------------------------------------
// Shipping setup
//--------------------------------------------------------
define("EW_SHIPPING", 5, TRUE); // Shipping flat rate, or if shipping=0 then no shipping 

//--------------------------------------------------------
// Pagination 
//--------------------------------------------------------
define("EW_PAGINATION", 8, TRUE); // 6 products for page