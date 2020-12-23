<?php

use IvanoMatteo\LaravelDbMutex\Models\DBMutex;

trait HasDbMutex  {


    function dbmutex(){
        return $this->morphOne(DBMutex::class, 'model');
    }


    function usingMutex(callable $callback){
        $result = null;
        \DB::transaction(function () use(&$result,$callback){
            $m = $this->dbmutex()->lockForUpdate()->firstOrNew();
            $m->updated_at = now();
            $m->save();
            $callback();
            $result = $m->updated_at;
        });
        return $result;
    }

}
