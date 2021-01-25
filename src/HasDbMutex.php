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

    function usingDbMutex(callable $callback, ?array $optLock = null, $name = 'default')
    {
        $result = null;
        \DB::transaction(function () use (&$result, $callback, $optLock, $name) {

            $m = $this->dbmutex()
                ->lockForUpdate()
                ->firstOrCreate(['name' => $name]);


            if (isset($optLock['counter'])) {
                if ($optLock['counter'] != $m->counter) {
                    abort(412, 'Elemento aggiornato da un\'altra finestra/sessione. Ricaricare la sezione corrente o la pagina.');
                }
            }
            if (isset($optLock['model_updated_at'])) {
                if ($optLock['model_updated_at'] != $this->getOriginal(static::UPDATED_AT)) {
                    abort(412, 'Elemento aggiornato da un\'altra finestra/sessione. Ricaricare la sezione corrente o la pagina.');
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
