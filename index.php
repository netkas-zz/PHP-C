<?PHP
	include('php-c.php');

	$Config = new php_c_config();
	$Config->Accounts = array('root' => 'admin');
	$Config->SecretKey = 'SDAHJ9832NJ8FD2UNF32FN9328FN8923F9328FHNJ';

	$PHP_C = new php_c($Config);
	$PHP_C->initialize();
?>