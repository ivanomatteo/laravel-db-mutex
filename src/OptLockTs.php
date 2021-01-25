<?php

namespace IvanoMatteo\LaravelDbMutex;

use Carbon\Carbon;
use IvanoMatteo\LaravelDbMutex\Models\DbMutex;

trait OptLockTs
{


    public function initializeOptLockTs()
    {
        $this->makeVisible([static::UPDATED_AT]);
        $this->fillable[] = static::UPDATED_AT;
    }

    public function checkOptLock($updated_at)
    {
        if (is_string($updated_at)) {
            $updated_at = new Carbon($updated_at);
        }

        return $this->getOriginal(static::UPDATED_AT) == $updated_at;
    }

    public function authorizeOptLock($updated_at)
    {
        if (!$this->checkOptLock($updated_at)) {
            abort(412, 'Elemento aggiornato da un\'altra finestra/sessione. Ricaricare la sezione corrente o la pagina.');
        }
    }

    public static function bootOptLockTs()
    {
        if (empty(static::$optLockNoAuto)) {
            static::updating(function ($model) {
                if ($model->isDirty(static::UPDATED_AT)) {
                    abort(412, 'Elemento aggiornato da un\'altra finestra/sessione. Ricaricare la sezione corrente o la pagina.');
                }
                unset($model->{static::UPDATED_AT});
            });
        }
    }
}
