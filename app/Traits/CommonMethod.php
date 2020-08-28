<?php


namespace App\Traits;

use File;
trait CommonMethod
{
    //check FIle exist or not
    public function checkFileAlreadyCreateOrNot($filePath,$fileName){
        $path=app_path($filePath.'/'.$fileName.'.php');
        if(!file_exists($path))
        {
            return true;
        }else{
            return false;
        }
    }




    //folder create or directory create method
    public function makeFolderOrDirectory($FolderPath){
        $path=app_path($FolderPath);
        if(!file_exists( $path))
        {
            File::makeDirectory( $path,$mode=0777,true,true);
        }//end file create option ;
    }
}
