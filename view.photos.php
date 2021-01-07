<?php
// view.photos.php

class ViewPhotos {

    private $photo_dir = 'minstagram_uploads/';
    private $allPhotos = []; 

    function __construct(){
        //print "Constructing " . __CLASS__ . "\n <br>";
        $this->getPhotos();
    }

    public function getAllPhotosString(){
        //return $this->allPhotos;
        /*
        $resultJSON = json_encode( $this->allPhotos );
        echo $resultJSON;
        */
        $resultString = json_encode( $this->allPhotos );
        echo $resultString;
    }

    public function getAllPhotos(){
        return $this->allPhotos;
    }

    private function getPhotos(){
        //echo 'getPhotos <br>';

        $files = scandir( $this->photo_dir , 0 );
        //$resultA = array();
        for($i = 0; $i < count($files); $i++){
            $file = $files[$i];
            
            $extension = pathinfo($file,PATHINFO_EXTENSION);
            //echo $extension;
            
            /*
            // removing '.' and '..' entries in the files
            if( strcmp ( '.' , $file ) !== 0 ){
                if( strcmp( '..', $file ) !== 0 ){
                    // Only for macOS
                    if( strcmp( '.DS_Store', $file ) !== 0 ){
                        //echo $file;
                        //array_push($resultA, $file);
                        array_push( $this->allPhotos, $file);
                    }// if
                }// if
            }// if
            */

            if( $extension == 'jpg' ){
                array_push( $this->allPhotos, $file );
            }

        }// for
        
    }

    function __destruct() {
        //print "Destroying " . __CLASS__ . "\n";
    }

}

?>