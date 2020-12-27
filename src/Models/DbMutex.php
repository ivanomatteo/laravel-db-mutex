<?php

namespace IvanoMatteo\LaravelDbMutex\Models;

use Illuminate\Database\Eloquent\Model;

class DBMutex extends Model
{
    protected $table = "db_mutexes";

    protected $guarded = [];

    protected $visible = ['name','counter','updated_at'];
}
