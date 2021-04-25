<?php 

function connection() {  		// creates connection
	$config = json_decode(file_get_contents('config.php'), TRUE);
	if (is_null($config)) {
		echo "Please first specify username, password and host for DB connection.";
	}else {
		$conn = new mysqli($config[0], $config[1], $config[2], $config[3]);   //config[] includes db info uname, pwd, host and dbname
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		return $conn;
	}
}


if (isset($argv[1])) {
	switch ($argv[1]) {
		case "--help":
			echo "--file: The name of the CSV file you want to parse.
			\r\n--create_table: Creates MySQL users table.
			\r\n--dry_run: This will run with --file directive in case you only want to run the script without creating any table.
			\r\n-u: MySQL username.
			\r\n-p: MySQL password.
			\r\n-h: MySQL host
			\r\n-db: MySQL DB";
			break;
		
		case "-u":
		case "-p":
		case "-h":
		case "-db":
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
				if ($val == "-db") {
					$db = $argv[$key + 1];
				}
			}
			
			// save db configurations
			if (isset($uname) && isset($pwd) && isset($host) && isset($db)) {
				$config = [$host, $uname, $pwd, $db];
				$content = json_encode($config);
				file_put_contents('config.php', $content);
				echo "configurations saved successfully.";
			}else{
				echo "Please specify username, password, host and DB for connection, all in one go.";
			}	
			break;
			
		case "--create_table":
			// sql to create table
			$sql = "CREATE TABLE users (
			name VARCHAR(30) NOT NULL,
			surname VARCHAR(30) NOT NULL,
			email VARCHAR(50) PRIMARY KEY,
			reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
			$conn = connection();
			if (isset($conn)) {
				if ($conn->query($sql) === TRUE) {
					echo "Table users created successfully";
				} else {
					echo "Error creating table: " . $conn->error;
				}
			}
			break;
		
		default:
			echo "type --help to see options.";
	}
}else {
	echo "Welcome to the PHP CLI application, type --help to see options.";
}