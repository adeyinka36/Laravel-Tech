<?php

use App\Models\Product;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cu {file=null} {--e=hide}', function () {
    $filename=$this->argument("file");
    if($filename!=="null"){
    $opt=$this->option("e");
    $error=[];

    $files= scandir("csv");
    $csvFile=null;
    $fileToupload;
    foreach($files as $file){
       if($file==$filename.".csv"){
          $csvFile=$file;
       }
    }
    if(!$csvFile){
        $this->comment(
           "There is no CSV formated file in the CSV folder. Please upload  a file of the right format"
          );
    }
    else{
       $data= file("csv/".$csvFile);
       $csvExploded=[];
       $errorLines=[];
       $errorRows=[];
       $totalRows=0;
       for($i =1; $i<count($data);$i++){
        //    check deliminator here
        if(strpos($data[$i],"|")){
           $row=explode("|",$data[$i]);
           if(array_key_exists(0,$row) && array_key_exists(1,$row) && array_key_exists(2,$row)){
               if(array_key_exists(3,$row)){
               if((int) $row[3]>0  && (int) $row[3]<(int) $row[2]){
                   array_push($csvExploded,$row);
               }
               else{
                array_push($errorLines,"Special price has to be less than Normal price and cannot be negative or 0 in row ".($i+1) ." of uploaded csv file "."sku: ". $row[0]);
                array_push($errorRows,$i);
               }
            }
            else{
                array_push($csvExploded,$row);
            }
           }
               else{
                   array_push($errorLines,"All fields except special_price are requied in row ".($i+1) ." of uploaded csv file "."sku: ". $row[0]);
                   array_push($errorRows,$i);
                  
               }
           }
        
        else{
           array_push($errorLines,"Wrong delimeter used on line ".($i+1) ." of uploaded csv file "."sku: ". $row[0]);
           array_push($errorRows,$i);
        }
        $totalRows=$i-1;
        $errorRowsCount=count(array_unique($errorRows));
       }
       $updateCount=0;
       $createCount=0;
       foreach($csvExploded as $c){
           $check = Product::firstOrNew(["sku"=>$c[0]]);
           if($check->exists){
              $updateCount++;
              $check->sku=$c[0];
              $check->description=$c[1];
              $check->normal_price=$c[2];
              if(key_exists(3,$c)){ $check->special_price=$c[3];}
              $check->save();
           }else{
               $createCount++;
              $check->sku=$c[0];
              $check->description=$c[1];
              $check->normal_price=$c[2];
              if(key_exists(3,$c)){ $check->special_price=$c[3];}
              $check->save();
           }

       }
       $this->comment(
         "Rows in file ".$totalRows.
         "\nRows with errors ".$errorRowsCount.
         "\nRows updated ".$updateCount.
         "\nRows created ".$createCount
       );


    //    if error display option is chosen


       if($opt !== "hide"){
            "Here arr the errors\n";
           print_r($errorLines);
         
       }
    }
}else{
$this->comment(
    "Please enter a filename in command line "
);
return false;
}
    
})->purpose('Laravel boss test');
