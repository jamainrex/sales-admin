<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountOwnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_owner', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('owner_id');
            $table->timestamps();

            $table->unique(['account_id', 'owner_id']);

            $table->foreign('account_id')
              ->references('id')
              ->on('accounts')->onDelete('cascade');
        
            $table->foreign('owner_id')
              ->references('id')
              ->on('owners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_owner');
    }
}
