<?php

namespace IvanoMatteo\LaravelDbMutex;

use IvanoMatteo\LaravelDbMutex\Models\DbMutex;

trait HasDbMutex
{


    function dbmutex()
    {
        return $this->morphMany(DbMutex::class, 'model');
    }

    function scopeWithDbMutex($q, $name = 'default')
    {
        $q->with(['dbmutex' => function ($q) use ($name) {
            $q->where('name', '=', $name);
        }]);

        return $q;
    }

    function usingDbMutex(callable $callback, $optLockCounter = null, $name = 'default')
    {
        $result = null;
        \DB::transaction(function () use (&$result, $callback, $optLockCounter, $name) {

            $m = $this->dbmutex()
                ->lockForUpdate()
                ->firstOrCreate(['name' => $name]);

            if (isset($optLockCounter)) {
                if ($optLockCounter != $m->counter) {
                    abort(412, 'record aggiornato da un\'altra finestra/sessione');
                }
            }

            $m->counter++;
            $m->save();

            $callback();
            $result = $m;
        });
        return $result;
    }
}
