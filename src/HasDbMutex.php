<?php

namespace IvanoMatteo\LaravelDbMutex;

use IvanoMatteo\LaravelDbMutex\Models\DbMutex;

trait HasDbMutex
{


    function dbmutex()
    {
        return $this->morphMany(DbMutex::class, 'model');
    }

    function usingDbMutex(callable $callback, ?array $optimistic_lock = null, $name = 'default')
    {
        $result = null;
        \DB::transaction(function () use (&$result, $callback, $optimistic_lock, $name) {
            
            $m = $this->dbmutex()
                ->lockForUpdate()
                ->firstOrCreate(['name' => $name]);

            if (isset($optimistic_lock)) {
                $updated_at = $optimistic_lock['updated_at'] ?? null;
                $counter = $optimistic_lock['counter'] ?? null;
                if (!isset($updated_at) && !isset($counter)) {
                    abort(400, 'parametro mancante: updated_at o count');
                }
                if ((isset($counter) && $counter !== $m->counter) ||
                    (isset($updated_at) && $updated_at !== $m->updated_at)
                ) {
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
