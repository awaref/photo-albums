<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPhotosTable extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable();
            $table->foreign('album_id', 'album_fk_9003044')->references('id')->on('albums');
        });
    }
}
