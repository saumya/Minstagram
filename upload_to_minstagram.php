<?php 
// Setting the Server Time Zone
date_default_timezone_set('Asia/Kolkata');
//
$app_function = (function(){
	//
	// Database services
	//
	/*
	$db_connect = function(){
		$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
		$pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
		return $pdo;
	};
	*/

	// Create a Database and Initilise Table for the first time
    // If not present, make it
    $initDBTable = function(){
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        
        try {
            $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
            $sql_statement = "CREATE TABLE IF NOT EXISTS minstagram (
                                                            id INTEGER PRIMARY KEY,
                                                            title TEXT,
                                                            photo BLOB,
                                                            photo_name TEXT )";
            $pdo->exec($sql_statement);
        } catch (PDOException $e) {
            echo 'Exception : Create Table';
            echo $e->getMessage();
        }
        
	};// initDBTable/
	
	$save_photo_in_db = function($f_name, $file_data_to_store){
		//$pdo = $db_connect();
		//
		$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
		$pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
		//
		$sql = "INSERT INTO minstagram(photo_name, photo)" . "VALUES(:p_name, :p_data)";
		$stmt = $pdo->prepare($sql);
		try {
			$stmt->bindParam(':p_name', $f_name);
			$stmt->bindParam(':p_data', $file_data_to_store, \PDO::PARAM_LOB);
			$stmt->execute();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		
	};
	$get_last_id = function(){
		// ref: https://www.php.net/manual/en/pdostatement.fetch.php
		//$pdo = $db_connect();
		
		$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
		$pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );

		$sql = "SELECT * FROM minstagram";
		$result = $pdo->query( $sql );
		
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$data = json_encode( $result->fetchAll() );
		
		return $data;
	};
	// Database services/
	$writeTheResultForFrontEnd = function($aResult){
		// This is needed for the Front-End Application
		// Unless we write something back, the Frontend application can not know
		// the return type from the server.
		// Frontend is waiting for a return from the fetch() call, that did trigger this file
		//
		$resultJSON = json_encode($aResult);
		echo $resultJSON;
	};

	$write_json_file = function(){
		$photo_dir = 'minstagram_uploads/';
		$allPhotos = [];
		//
		$files = scandir( $photo_dir , 0 );
		for($i = 0; $i < count($files); $i++){
			$file = $files[$i];
			$extension = pathinfo($file,PATHINFO_EXTENSION);
			if( $extension == 'jpg' ){
				array_push( $allPhotos, $file );
			}
		}
		//
		$jsonString = '';
		$jString = '';
		$count = 0;
		foreach( $allPhotos as $key=>$fileObj ){
			$count++;
			if( count($allPhotos) == $count ){
				$jString .= '{"file":"' . $fileObj . '"}';
			}else{
				$jString .= '{"file":"' . $fileObj . '"},';
			}
		}
		$jsonString = '[' . $jString . ']' ;
		// Write to the file
		$fileToWrite = "minstagram_uploads/minstagram.json";
		$file_open_handle = fopen( $fileToWrite, 'w' );
		fwrite( $file_open_handle, $jsonString );
		fclose( $file_open_handle );
	}; // write_json_file/
	// Utility
	$getImageFolderDetails = function(){
		// Image files count
		$files = scandir('minstagram_uploads/');
		$files_only = array_diff( $files, array('..', '.', '.DS_Store', 'minstagram.json', 'minstagram.txt') );
		$num_images_on_this_folder = count($files_only);
		return $num_images_on_this_folder;
	};
	// Utility/
	//
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_FILES['files'])) {
			$errors = [];
			
			$path = 'minstagram_uploads/';
			$extensions = ['jpg', 'jpeg', 'png', 'gif'];
			$all_files = count($_FILES['files']['tmp_name']);
			$aResult = array();

			for ($i = 0; $i < $all_files; $i++) {
				
				// Name the uploaded file
				$file_name = $_FILES['files']['name'][$i];
				$file_tmp = $_FILES['files']['tmp_name'][$i];
				$file_type = $_FILES['files']['type'][$i];
				$file_size = $_FILES['files']['size'][$i];
				$file_ext = strtolower(end(explode('.', $_FILES['files']['name'][$i])));
				
				$file = $path . $file_name;
				$file_name_in_server = $path . ( $numFiles = $getImageFolderDetails()+1 ) . '.' . $file_ext;
				
				if (!in_array($file_ext, $extensions)) {
					$errors[] = 'Extension not allowed: FullName=' . $file_name . ' extension=' . $file_ext . ' type=' . $file_type;
				}// if
				if ($file_size > 2097152) {
					$errors[] = 'File size exceeds limit: FullName=' . $file_name . ' extension=' . $file_ext . ' type=' . $file_type;
				}// if
				if (empty($errors)) {
					// Before Saving the File in Database, 
					// check if Database is present or not
					// If not, create the DB.
					$initDBTable();
					// Database and putting the file in the database
					$save_photo_in_db( $file_name, file_get_contents( $file_tmp ) );
					// Move the file to desired location
					$result = move_uploaded_file($file_tmp, $file_name_in_server); // Renaming the uploaded file in server
					array_push( $aResult, $result);
				}// if

			}// for

			$writeTheResultForFrontEnd($aResult); 
			$write_json_file();

			if ($errors) print_r($errors);

		}// if/
	} else {
		// not a POST request
		echo '---------------------------' . '<br />';
		echo 'Total Files=' . $getImageFolderDetails()  . '<br />';
		echo 'Not a POST request!' . '<br/>';
		echo '---------------------------' . '<br />';
	} // if/
});
// =================================================
// Execute the function
$app_function();
// =================================================

