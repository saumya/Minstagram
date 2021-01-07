<?php
// 
// Version: 1.0.0
//



$run_the_self_executing_application = (function(){
    //$SQLite_file_path = 'minstagram_uploads/phpsqlite_1.db';
    $SQLite_file_path = 'phpsqlite.db';
    $get_ui_data = function(){
        // ref: https://www.php.net/manual/en/wrappers.php.php
        $str_json = file_get_contents('php://input');
        $obj = json_decode( $str_json );
        //echo $obj->{'title'};
        return $obj->{'title'};
    };
    // Database
    $get_last_id = function(){
        // ref: https://www.php.net/manual/en/pdostatement.fetch.php
        //$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        $sql = "SELECT * FROM minstagram";
        $sth = $pdo->prepare( $sql );
        $sth->execute();
        //$result = $sth->fetchAll( PDO::FETCH_ASSOC );
        $result = $sth->fetchAll();
        $total_num_rows = count($result);
        return $total_num_rows;
    }; // $get_last_id/
    
    $set_title_for_the_photo_with_id = function( $photo_id, $photo_title){
        
        $data = [
            'photo_id'=>$photo_id,
            'photo_title'=>$photo_title
        ];
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        $sql = "UPDATE minstagram SET title=:photo_title WHERE id=:photo_id";
        $update_statement = $pdo->prepare( $sql );
        $update_statement->execute($data);
        return $update_statement->rowCount();
    }; // $set_title_for_the_photo_with_id/

    $initDBTable = function($PATH_TO_SQLITE_FILE){
        //$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        
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

    $savePhoto = function($f_name, $file_data_to_store){
        //$PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        $sql = "INSERT INTO minstagram(photo_name, photo)" . "VALUES(:p_name, :p_data)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':p_name', $f_name);
        $stmt->bindParam(':p_data', $file_data_to_store, \PDO::PARAM_LOB);
        $stmt->execute();
    };// savePhoto/
    
    // Database/
    //
    // Checks the folder in which photos get uploaded and counts the number of photos
    $create_file_name = function(){
        $path = 'minstagram_uploads/';
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $xfiles = ['.', '..', '.DS_Store', 'minstagram.json','minstagram.txt'];
        $all_files = scandir( $path );
        $all_image_files = array_diff($all_files,$xfiles);
        $num_image_files = count($all_image_files);
        $next_file_name = $num_image_files + 1;
        return $next_file_name;
    };// create_file_name/
    // 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ( isset($_POST) ){
            $initDBTable( $SQLite_file_path );

            $photo_id = ($create_file_name()-1);
            $photo_title = $get_ui_data();
            // TODO: Fix this
            $result = $set_title_for_the_photo_with_id($photo_id, $photo_title);
            
            echo $photo_id.'-'.$photo_title.'<br/> ';

            if($result==0){
                echo 'Title Update Failed!';
            }else{
                echo 'Title update Success.';
            }
            //
        }else{
            echo '{ "result" : "Nothing From FrontEnd" }';
        }
    }else{
        echo '{ "result" : "Not POST X" }';
    }
    //
});

// =================================================
// Execute the function
$run_the_self_executing_application();
// =================================================


