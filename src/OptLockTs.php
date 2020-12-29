<?php

namespace IvanoMatteo\LaravelDbMutex;

use IvanoMatteo\LaravelDbMutex\Models\DbMutex;

trait OptLockTs
{
    public function initializeOptLockTs()
    {
        $this->makeVisible(['updated_at']);
        $this->fillable[] = 'updated_at';
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
