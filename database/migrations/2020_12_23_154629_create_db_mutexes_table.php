<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDBMutexesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_mutexes', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->string('name',40)->index();

            $table->bigInteger('counter')->unsigned()->default(0);

            $table->unique(['model_id','model_type','name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('db_mutexes');
    }
}
