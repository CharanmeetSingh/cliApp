<?php 

if (isset($argv[1]) || isset($arg[2])) {
	switch ($argv[1]) {
		case "--help":
			echo "--file: this is the name of the CSV file you want to parse.
			\r\n--create_table: this will create MySQL users table.
			\r\n--dry_run: this will run with --file directive in case you only want to run the script without creating any table.
			\r\n-u: MySQL username.
			\r\n-p: MySQL password.
			\r\n-h: MySQL host.";
			break;
		
		default:
			echo "type --help to see options.";
	}
}else {
	echo "Welcome to the PHP CLI application, type --help to see options.";
}