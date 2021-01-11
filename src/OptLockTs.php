<?php

namespace IvanoMatteo\LaravelDbMutex;

use IvanoMatteo\LaravelDbMutex\Models\DbMutex;

trait OptLockTs
{
    public function initializeOptLockTs()
    {
        $this->makeVisible([static::UPDATED_AT]);
        $this->fillable[] = static::UPDATED_AT;
    }

    public static function bootOptLockTs()
    {
        static::updating(function ($model) {
            if ($this->isDirty(static::UPDATED_AT)) {
                abort(412, 'record aggiornato da un\'altra finestra/sessione');
            }
            unset($this->{static::UPDATED_AT});
        });
    }
}
