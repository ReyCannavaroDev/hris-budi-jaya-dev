<?php
namespace App\Cores;

class Bootstrap
{
    public function register( $app ){
        try{
            new Frontend( $app );
        }catch(\Exception $e){
            // ff($e);
        }
        
        return $app;
    }
}
