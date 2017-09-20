<?PHP
	include('php-c.php'); // Include the PHP-C Class

	$Config = new php_c_config(); // Declare a configuration object
	$Config->Accounts = array('root' => 'admin'); // Set the user accounts you want
	$Config->SecretKey = 'SDAHJ9832NJ8FD2UNF32FN9328FN8923F9328FHNJ'; // This SecretKey contains unicode chracters, 20 or more Characters.

	$PHP_C = new php_c($Config); // Declare a PHP-C Object w/ the Configuration Object
	$PHP_C->initialize(); // Initialize the PHP-C Script (This will end the session as well)
?>
