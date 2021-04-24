<?php 

if (isset($argv[1])) {
	switch ($argv[1]) {
		case "--help":
			echo "--file: The name of the CSV file you want to parse.
			\r\n--create_table: Creates MySQL users table.
			\r\n--dry_run: This will run with --file directive in case you only want to run the script without creating any table.
			\r\n-u: MySQL username.
			\r\n-p: MySQL password.
			\r\n-h: MySQL host";
			break;
		
		case "-u":
		case "-p":
		case "-h":
			$uname = "";
			$pwd = "";
			$host = "";
			foreach ($argv as $key => $val) {
				if ($val == "-u") {
					$uname = $argv[$key + 1];
				}
				if ($val == "-p") {
					$pwd = $argv[$key + 1];
				}
				if ($val == "-h") {
					$host = $argv[$key + 1];
				}
			}
			
			// Make connection here
			break;
			
		case "--create_table":
			print_r($argv);
			break;
		
		default:
			echo "type --help to see options.";
	}
}else {
	echo "Welcome to the PHP CLI application, type --help to see options.";
}