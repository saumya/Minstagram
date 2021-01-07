<?php
/** 
* writefile.class.php
* version: 1.0.0
* usage: Writes to a JSON file
*
*/

class WriteFile {

    private $photo_dir = 'minstagram_uploads/';
    private $allPhotos = [];
    private $fileToWrite = "minstagram_uploads/minstagram.json";
    private $dataToWrite = '';
    
    function __construct( $data=null ){
        $this->dataToWrite = $data;
    }
    
    public function writeToFile(){
        $Handle = fopen( $this->fileToWrite, 'w' );
        
        $jsonString = '';
        $jString = '';
        $count = 0;
        
        foreach( $this->dataToWrite as $key=>$fileObj ){
            $count++;
            if( count($this->dataToWrite) == $count ){
                $jString .= '{"file":"' . $fileObj . '"}';
            }else{
                $jString .= '{"file":"' . $fileObj . '"},';
            }
        }
        $jsonString = '[' . $jString . ']' ;

        //fwrite( $Handle, '[' );
        //fwrite( $Handle, $jString );
        //fwrite( $Handle, ']' );

        fwrite( $Handle, $jsonString );
        fclose( $Handle );
    }

    public function find_all_files_and_write_info_to_a_file(){
        echo '<br> find_all_files_and_write_info_to_a_file : wip <br><br>';
        
        $aPhotos = $this->getPhotos();
        foreach ($aPhotos as $key => $value) {
            /*
            echo "$key => $value" . filesize($value) . '<br>';
            
            foreach( pathinfo($value) as $k => $itemDetails){ 
                echo $k . '=' . $itemDetails . '<br>'; 
            }
            */
            echo '------------------------------ <br>';
            //echo __DIR__ . '/' . $value .'<br>';
            //echo __DIR__ . '<br>';
            //echo $value .'<br>';
            //$file_path = __DIR__ . '/' . $this->photo_dir . $value;
            $file_path = $this->photo_dir . $value;
            //echo $file_path . '<br>';

            //echo '<br>';

            //echo 'size:' . filesize( __DIR__. '/' .$value ) . '<br>';

            if( file_exists( $file_path ) )
            {
                echo 'Exist-' . ( $file_path ) . '<br>';
                echo 'File Size-' . filesize( $file_path ) / 1000 . 'kb <br>';
            }else{
                echo 'XXX -' . $value . '<br>';
            }
        }// foreach/

        //$this->getFileDetails( '/Users/saumya/Downloads' );
        $this->getFileDetails( '/Users' );
        //$this->getFileDetails( '/' );
        $this->getFileDetails( '/Users/Shared' );
        $this->getFileDetails( '/Users/saumya' );
        
        echo '<br> find_all_files_and_write_info_to_a_file : wip <br>';
    }

    private function getPhotos(){
        $files = scandir( $this->photo_dir , 0 );
        //$resultA = array();
        for($i = 0; $i < count($files); $i++){
            $file = $files[$i];
            $extension = pathinfo($file,PATHINFO_EXTENSION);
            if( $extension == 'jpg' ){
                array_push( $this->allPhotos, $file );
            }
        }// for
        return $this->allPhotos;
    }

    private function getFileDetails($path)
    {
        echo '------------------ <br>';
        echo 'path-'. $path . '<br>';
        echo '------------------ <br>';

        echo '------------------------------------------------------ <br>';

        $files = scandir($path, 0);
        foreach ($files as $key => $file) {
            //echo 'key-' . $key . ' : file-' . $file . ' | ' . filesize( $path.'/'.$file ) / 1000 .'kb <br>';
            $full_file_path = $path.'/'.$file;
            if( is_dir($full_file_path) ){
                echo '<strong>Folder-</strong>' . $full_file_path . ' ';
                echo ' ' . date ( "Y F d H:i:s.", filemtime( $full_file_path ) ) . '<br>';
            }else{
                echo '';
                echo $file . '  <strong>' . filesize( $full_file_path ) / 1000 .'kb</strong> ';
                echo ' ' . date ( "Y d H:i:s.", filemtime( $full_file_path ) );
                echo '<br>';
            }
            
        }

        echo '------------------------------------------------------ <br>';
    }

    function __destruct(){
        //echo 'WriteFile : Destruction';
    }
}


?>