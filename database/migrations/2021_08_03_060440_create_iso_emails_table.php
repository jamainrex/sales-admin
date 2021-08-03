<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIsoEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iso_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iso_id');
            $table->string('email', 50);
            $table->timestamps();

            $table->unique(['iso_id', 'email']);

            $table->foreign( 'iso_id' )
                    ->references( 'id' )
                    ->on( 'iso' )
                    ->onDelete( 'cascade' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iso_emails');
    }
}
