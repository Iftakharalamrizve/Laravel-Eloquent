<?php


namespace App\Traits;

use File;
use Artisan;
use Illuminate\Support\Str;
trait RepositoryPattern
{

     /**
     * The protected array define all necessary file folder hints
     *
     * @var array
     */
    protected $repositoryPattern = [
        'Interfaces',
        'Repositories',
        'Requests',
        'Controllers',
        'Views'
    ];

    /**
     *this method user for build structure for repository pattern
     *
     * @var
     */
    public function RepositoryPatternGenerate($moduleName)
    {

        foreach ($this->repositoryPattern as $key=>$item){
            switch ($key){
                case 0:
                    $ModelFolderPath="/".$item;
                    $this->makeFolderOrDirectory($ModelFolderPath);
                    $this->InterfaceGenerator($item,$moduleName);
                    break;
                case 1:
                    $ModelFolderPath="/".$item;
                    $this->makeFolderOrDirectory($ModelFolderPath);
                    $this->RepositoryGenerator($this->repositoryPattern[0],$item,$moduleName);
                    break;
                case 2:
                    $ModelFolderPath="/".$item;
                    $this->RequestGenerator($ModelFolderPath,$item,$moduleName);
                    break;
                case 3:
                    $ModelFolderPath="/".$item;
                    $this->ControllerGenerator($ModelFolderPath,$item,$moduleName);
                    break;
            }
        }
    }

    public function getStub($type, $stubName)
	{
		return file_get_contents(app_path('Stubs/' . $type . '/' . $stubName . '.text'));
	}


	public function InterfaceGenerator($modulePath,$moduleName)
    {

        $interFaceText=$this->getStub(ucfirst($modulePath),Str::singular(ucfirst($modulePath)));
        $moduleName=Str::singular(ucfirst($moduleName));
        $interFaceTemplate = str_replace ( [ '{ModuleName}' ] , [ $moduleName ] , $interFaceText );
        $filePath='/'.$modulePath.'/'.$moduleName.Str::singular(ucfirst($modulePath)).'.php';
        $this->makeFile($filePath);
        file_put_contents(app_path($filePath),$interFaceTemplate);
        $this->info("Your Interfaces create Successfully");
    }


    public function  RepositoryGenerator($interFacePath,$modulePath,$moduleName)
    {
        $nameSpace='App\\'.$interFacePath;
        $repositoryText=$this->getStub("Repositories","Repository");
        $moduleName=Str::singular(ucfirst($moduleName));
        $modulePathSingular=Str::singular(ucfirst($modulePath));
        $repositoryTemplate = str_replace ( ['{ModulePath}','{ModuleName}','{InterFacePath}' ] , [ $moduleName,$modulePathSingular,$nameSpace] , $repositoryText );
        $filePath='/'.$modulePath.'/'.$moduleName.$modulePathSingular.'.php';
        $this->makeFile($filePath);
        file_put_contents(app_path($filePath),$repositoryTemplate);
        $this->info("Your Repositories create Successfully");
    }

    public function RequestGenerator($ModalFolderPath,$folderName,$moduleName)
    {
        //check modal is already create or not
        $fileName=str::singular(ucfirst($moduleName)).str::singular(ucfirst($folderName));
        $fileExistOrNot=$this->checkFileAlreadyCreateOrNot($ModalFolderPath,$fileName);
        if($fileExistOrNot){
            Artisan::call('make:request  '.$fileName);
            $this->info("Your Request Is Successfully Create");
            return true;
        }else{
            $this->warn("Your Request is not create because It's already exist ");
            return false;
        }
    }

    public function ControllerGenerator($ModalFolderPath,$folderName,$moduleName){
        //check modal is already create or not
        $fileName=str::singular(ucfirst($moduleName)).str::singular(ucfirst($folderName));
        $fileExistOrNot=$this->checkFileAlreadyCreateOrNot($ModalFolderPath,$fileName);
        if($fileExistOrNot){
            Artisan::call('make:controller  '.$fileName);
            $this->info("Your Controller Is Successfully Create");
            return true;
        }else{
            $this->warn("Your Controller is not create because It's already exist ");
            return false;
        }
    }




}
