<?php 

function connection() {  		// creates connection
	$config = json_decode(file_get_contents('config.php'), TRUE); //config[] includes db info: uname, pwd, host and dbname
	if (is_null($config)) {
		echo "Please first specify username, password and host for DB connection.";
		exit();
	}else {
		try {
			$conn = new mysqli($config[0], $config[1], $config[2], $config[3]);
			if ($conn->connect_error) {
				throw new Exception("Connection failed: " . $conn->connect_error);
			}
		}
		catch (Exception $e) {
			echo $e->getMessage();
			exit();
		}
		return $conn;
	}
}

function parse($file, $dry) {
	if ($dry === FALSE) {
		$conn = connection(); // Creating connection
	}
	$file = fopen($file, 'r');
	while ($row = fgetcsv($file)) {
		if ($row[0] != "name" && $row[1] != "surname" && $row[2] != "email") {  // Omitting column names
			// Insertion into table
			if (filter_var(trim($row[2]), FILTER_VALIDATE_EMAIL)) { // Validating emails
				$name = ucfirst(strtolower($row[0]));
				$name = trim(trim($name), '!'); // First trim for whitespace then second to remove !
				$surname = trim(ucfirst(strtolower($row[1])));
				$email = trim(strtolower($row[2]));
				
				if ($dry === FALSE) {
					$name = $conn->real_escape_string($name); // Escaping name for special char
					$surname = $conn->real_escape_string($surname); // Escaping surname for special char
					$email = $conn->real_escape_string($email); // Escaping email for special char
					$sql = "INSERT INTO users (name, surname, email)
					VALUES ('$name', '$surname', '$email')";
					
					if ($conn->query($sql) === TRUE) {
					echo "\r\n New record created successfully";
					} else {
						echo "\r\n Error: " . $conn->error;
					}
					
				}else {
					echo "\r\n Email validation passed.";
				}
			}else {
				echo "\r\n Invalid email.";
			}
		}
	}
	fclose($file);
}


if (isset($argv[1])) {
	switch ($argv[1]) {
		case "--help":
			echo "\r\n--file: To parse the CSV file and making DB entry foreach row. Ex. --file [fileName] 
			\r\n--create_table: Creates MySQL users table
			\r\n--dry_run: To parse the CSV file without making any entry in DB. Ex. --file [fileName] --dry_run
			\r\n-u: MySQL username
			\r\n-p: MySQL password
			\r\n-h: MySQL host
			\r\n-db: MySQL DB
			\r\n--exit: Removes all DB configuration and exit the script\r\n";
			break;
		
		case "-u":
		case "-p":
		case "-h":
		case "-db":
			foreach ($argv as $key => $val) {
				if ($val === "-u") { 
					$uname = $argv[$key + 1];
				}
				if ($val === "-p") {
					$pwd = $argv[$key + 1];
				}
				if ($val === "-h") {
					$host = $argv[$key + 1];
				}
				if ($val === "-db") {
					$db = $argv[$key + 1];
				}
			}
			
			// Saving db configurations
			if (isset($uname) && isset($pwd) && isset($host) && isset($db)) {
				$config = [$host, $uname, $pwd, $db];
				$content = json_encode($config);
				file_put_contents('config.php', $content);
				echo "configurations saved successfully.";
			}else{
				echo "Please specify username, password, host and DB for connection, all at once. Ex. -u [uname] -p [pwd] and so forth.";
			}	
			break;
			
		case "--create_table":
			$conn = connection(); // Creating connection
			// sql to create table
			$sql = "CREATE TABLE users (
			name VARCHAR(30) NOT NULL,
			surname VARCHAR(30) NOT NULL,
			email VARCHAR(50) PRIMARY KEY)";
			
			if ($conn->query($sql) === TRUE) {
				echo "Table users created successfully";
			} else {
				echo "Error creating table: " . $conn->error;
			}
			break;
			
		case "--file":
			if (isset($argv[2]) && !isset($argv[3])) {
				$file = $argv[2];
				parse($file, FALSE);
			}else {
				if (!isset($argv[3])) {
					echo "Please specify the name of the file to be parsed.";
				}
			}
			
			if (isset($argv[3]) && $argv[3] === "--dry_run") {
				$file = $argv[2];
				parse($file, TRUE);
			}
			break;
			
		case "--exit":
			$config = json_decode(file_get_contents('config.php'), TRUE); //config[] includes db info: uname, pwd, host and dbname
			if (!is_null($config)) {
				$ex = fopen('config.php', 'w');
				fclose($ex);
				echo "Removed saved configurations.";
			}
			exit();
			break;
			
		
		default:
			echo "type --help to see options.";
	}
}else {
	echo "Welcome to the PHP CLI application, type --help to see options.";
}