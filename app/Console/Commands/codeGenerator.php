<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use File;
use Route;
use Artisan;

use App\Traits\OtherPattern;
use App\Traits\CommonMethod;
use App\Traits\RepositoryPattern;
class codeGenerator extends Command
{
    //use Traits
    use RepositoryPattern;

    use OtherPattern;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pattern:web';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' This command use for auto generate and create folder structure ';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * This property get to type value repository pattern or not
     *
     * @var boolean
     */
     protected $patternType;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //enter Pattern name
        $moduleName = $this->ask('Enter Module Pattern Name :');
        //migration and model make if find patternName
        $migrationModalCreateStatus=isset($moduleName)?$this->makeModalMigrationPattern($moduleName):false;
        //modal and migration status message show
        $migrationModalCreateStatus?$this->info("Your Migration And Model Create successfully"):$this->warn("Your Modal Migration already Created");
        //make controller
        //have 2 type code style 1 repository pattern
        $this->patternType=$this->choice('You Need Repositories Pattern ?', ['Yes', 'No'],1);
        if($this->patternType=="Yes"){
            //if select repository pattern
            $this->RepositoryPatternGenerate($moduleName);
        }else{
            //other general pattern
        }

    }



    //migration maker use pattern name

    private function  makeModalMigrationPattern($patternName){
        //check singular and check speech  and replace and check models file directory exist if no exist it's make file with permission
        $ModelFolderPath="/Models";
        $this->makeFolderOrDirectory($ModelFolderPath);
        //check modal is already create or not
        $fileExistOrNot=$this->checkFileAlreadyCreateOrNot($ModelFolderPath,$patternName);
        if($fileExistOrNot){
            Artisan::call('make:model Models/'.str_replace(' ','',Str::singular($patternName )) . ' -m');
            return true;
        }else{
            $this->warn("Your Modal Migration is not create because It's already exist ");
            return false;
        }

    }


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

    public function makeFile($FolderPath){
        $path=app_path($FolderPath);
        if(!file_exists( $path))
        {
//            fopen($path,$mode=0777,true,true);
            fopen($path,'w');
        }//end file create option ;
    }

}
