<?php
// 
// Version: 1.0.0
//



$run_the_self_executing_application = (function(){
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
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        $sql = "SELECT * FROM minstagram";
        $sth = $pdo->prepare( $sql );
        $sth->execute();
        //$result = $sth->fetchAll( PDO::FETCH_ASSOC );
        $result = $sth->fetchAll();
        $total_num_rows = count($result);
        /*
        for ($i = 0; $i < count($result); $i++)  {
            $row = $result[$i];
            //echo $row['id'] . ' | ' . $row['title'] . ' | ' . $row['photo_name'] . ' | ' . $row['photo'] .'<br/>';
            echo ' \n ';
            echo 'id=' . $row['id'] . ' | title=' . $row['title'] . ' | name=' . $row['photo_name'];
            echo ' \n ';
        }
        echo 'TotalNumber='. count($result);
        */
        //
        //echo 'get_last_id';
        return $total_num_rows;
    }; // $get_last_id/
    
    $set_title_for_the_photo_with_id = function($photo_id, $photo_title){
        
        $data = [
            'photo_id'=>$photo_id,
            'photo_title'=>$photo_title
        ];
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        //$sql = "SELECT * FROM minstagram";
        //$sql = "INSERT INTO minstagram (title) VALUES (:stringtitle)";
        $sql = "UPDATE minstagram SET title=:photo_title WHERE id=:photo_id";
        $update_statement = $pdo->prepare( $sql );
        $update_statement->execute($data);
        return $update_statement->rowCount();
    }; // $set_title_for_the_photo_with_id/
    
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
    };
    // 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ( isset($_POST) ){
            /*
            // Data from UI, the user typed title of the image
            echo '> From UI=' . $get_ui_data();
            
            // Get the files data from the folder & Make a new Name and save it in DB
            echo '> New FileName=' . $create_file_name();
            //
            // Check the DB and get the last id from the table
            // echo '> LastId=' . $get_last_id();
            // We do not need to check the DB for the latest 'id'
            // We can take that from the number of files
            echo '> LastId=' . ($create_file_name()-1);
            //
            */

            $photo_id = ($create_file_name()-1);
            $photo_title = $get_ui_data();
            $result = $set_title_for_the_photo_with_id($photo_id, $photo_title);
            //echo $result;
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

//
/*
$path = 'minstagram_uploads/';
$extensions = ['jpg', 'jpeg', 'png', 'gif'];
$xfiles = ['.', '..', '.DS_Store', 'minstagram.json','minstagram.txt'];
$all_files = scandir( $path );
$all_image_files = array_diff($all_files,$xfiles);
$num_image_files = count($all_image_files);
$next_file_name = $num_image_files + 1;

echo ( json_encode( $all_files ) );
echo '<br>';
print_r( $all_image_files );
echo '<br>';
echo( 'Next File Name =' . $next_file_name );
*/





//

