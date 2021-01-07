<?php

// Setting the Server Time Zone
date_default_timezone_set('Asia/Kolkata');


// Self-Executing Function
(function(){
    //
    $getTimeString = function(){
        //$dateFormat = "Y n j, g:i:s a";
        $dateFormat = "F j, Y, g:i:s a";
        $d = date($dateFormat) ;
        return $d;
    };
    //
    $db_connect = function(){
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        return $pdo;
    };
    //
    $db_get_data_make_json_file = function(){
        $PATH_TO_SQLITE_FILE = 'phpsqlite.db';
        $pdo = new \PDO( "sqlite:" . $PATH_TO_SQLITE_FILE );
        $sql = "SELECT * FROM minstagram";
        
        $result = $pdo->query( $sql );
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $data = $result->fetchAll();
        //$data = json_encode( $result->fetchAll() );
        //return $data;
        //echo $data; //Array
        //echo count($data);
        //print_r($data);
        //var_dump( $data );

        $jsonString = '';
        $jString = '';

        echo '[';
        for ($i=0; $i <count($data) ; $i++) { 
            $row = $data[$i];
            //echo 'id=' . $row['id'] . '- title=' . $row['title'] . '- name=' . $row['photo_name'];
            echo '{ "id" : ' . $row['id'] . ',';
            echo '"title" : "' . $row['title'] . '",';
            if( $i === (count($data)-1) ){
                echo '"name" : "' . $row['photo_name'] . '" }';
            }else{
                echo '"name" : "' . $row['photo_name'] . '" },';
            }// if/
            //
            $jString .= '{ "id" : ' . $row['id'] . ',';
            $jString .= '"title" : "' . $row['title'] . '",';
            $jString .= '"path" : "' . $row['id'] . '.jpg",';
            //$jString .= '"name" : "' . $row['photo_name'] . '" }';
            if( $i === (count($data)-1) ){
                $jString .= '"name" : "' . $row['photo_name'] . '" }';
            }else{
                $jString .= '"name" : "' . $row['photo_name'] . '" },';
            }
            //
        }// for/
        echo ']';

        $jsonString = '[' . $jString . ']' ;
        // Writing to JSON File
        $fileToWrite = "minstagram_uploads/minstagram_db.json";
        $file_open_handle = fopen( $fileToWrite, 'w' );
        fwrite( $file_open_handle, $jsonString );
        fclose( $file_open_handle );
        
        //
        //echo $data;
    };
    //
    //echo ($getTimeString());
    //echo( $db_get_data_make_json_file() );
    $db_get_data_make_json_file();
    //
})(); // End (function(){})(); /