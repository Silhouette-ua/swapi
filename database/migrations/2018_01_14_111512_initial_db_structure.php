<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitialDbStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planets', function ($table) {
            $table->increments('id');
            $table->string('name', 128)->nullable();
            $table->smallInteger('rotation_period')->unsigned()->nullable();
            $table->smallInteger('orbital_period')->unsigned()->nullable();
            $table->integer('diameter')->unsigned()->nullable();
            $table->string('climate', 64)->nullable();
            $table->string('gravity', 64)->nullable();
            $table->string('terrain', 128)->nullable();
            $table->tinyInteger('surface_water')->unsigned()->nullable();
            $table->bigInteger('population')->unsigned()->nullable();
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
        });

        Schema::create('films', function ($table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->tinyInteger('episode_id')->unsigned();
            $table->text('opening_crawl');
            $table->string('director', 128);
            $table->string('producer', 191);
            $table->timestamp('release_date')->nullable();
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
        });

        Schema::create('species', function ($table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('classification', 64)->nullable();
            $table->string('designation', 64)->nullable();
            $table->smallInteger('average_height')->unsigned()->nullable();
            $table->string('skin_colors', 191)->nullable();
            $table->string('hair_colors', 191)->nullable();
            $table->string('eye_colors', 191)->nullable();
            $table->smallInteger('average_lifespan')->unsigned()->nullable();
            $table->string('language', 64)->nullable();
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
        });

        Schema::create('vehicles', function ($table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('model', 64);
            $table->string('manufacturer', 128)->nullable();
            $table->integer('cost_in_credits')->unsigned()->nullable();
            $table->tinyInteger('length')->unsigned()->nullable();
            $table->smallInteger('max_atmosphering_speed')->unsigned()->nullable();
            $table->tinyInteger('crew')->unsigned()->nullable();
            $table->integer('passengers')->unsigned()->nullable();
            $table->integer('cargo_capacity')->unsigned()->nullable();
            $table->string('consumables', 64)->nullable();
            $table->string('vehicle_class', 64);
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
        });

        Schema::create('starships', function ($table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('model', 64);
            $table->string('manufacturer', 128)->nullable();
            $table->bigInteger('cost_in_credits')->unsigned()->nullable();
            $table->string('length', 32)->nullable();
            $table->string('max_atmosphering_speed', 32)->nullable();
            $table->integer('crew')->unsigned()->nullable();
            $table->integer('passengers')->unsigned()->nullable();
            $table->bigInteger('cargo_capacity')->unsigned()->nullable();
            $table->string('consumables', 64)->nullable();
            $table->decimal('hyperdrive_rating', 3, 1)->nullable();
            $table->tinyInteger('mglt')->unsigned()->nullable();
            $table->string('starship_class', 64);
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
        });

        Schema::create('people', function ($table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->smallInteger('height')->unsigned()->nullable();
            $table->decimal('mass', 10, 4)->nullable();
            $table->string('hair_color', 64)->nullable();
            $table->string('skin_color', 64)->nullable();
            $table->string('eye_color', 64)->nullable();
            $table->string('birth_year', 64)->nullable();
            $table->enum('gender', ['male', 'female', 'hermaphrodite', 'n/a'])->nullable();
            $table->integer('homeworld')->unsigned();
            $table->string('url', 191);
            $table->timestamp('created')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('edited')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('url');
            $table->foreign('homeworld')->references('id')->on('planets')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('people_to_species', function ($table) {
            $table->increments('id');
            $table->integer('people_id')->unsigned();
            $table->integer('species_id')->unsigned();
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('species_id')->references('id')->on('species')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('people_to_starships', function ($table) {
            $table->increments('id');
            $table->integer('people_id')->unsigned();
            $table->integer('starship_id')->unsigned();
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('starship_id')->references('id')->on('starships')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('people_to_vehicles', function ($table) {
            $table->increments('id');
            $table->integer('people_id')->unsigned();
            $table->integer('vehicle_id')->unsigned();
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('people_to_films', function ($table) {
            $table->increments('id');
            $table->integer('people_id')->unsigned();
            $table->integer('film_id')->unsigned();
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('film_id')->references('id')->on('films')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('starships_to_films', function ($table) {
            $table->increments('id');
            $table->integer('starship_id')->unsigned();
            $table->integer('film_id')->unsigned();
            $table->foreign('starship_id')->references('id')->on('starships')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('film_id')->references('id')->on('films')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('vehicles_to_films', function ($table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->integer('film_id')->unsigned();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('film_id')->references('id')->on('films')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('planets_to_films', function ($table) {
            $table->increments('id');
            $table->integer('planet_id')->unsigned();
            $table->integer('film_id')->unsigned();
            $table->foreign('planet_id')->references('id')->on('planets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('film_id')->references('id')->on('films')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('people_to_species');
        Schema::dropIfExists('people_to_vehicles');
        Schema::dropIfExists('people_to_starships');
        Schema::dropIfExists('people_to_films');
        Schema::dropIfExists('vehicles_to_films');
        Schema::dropIfExists('starships_to_films');
        Schema::dropIfExists('planets_to_films');
        Schema::dropIfExists('starships');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('species');
        Schema::dropIfExists('films');
        Schema::dropIfExists('people');
        Schema::dropIfExists('planets');
    }
}
