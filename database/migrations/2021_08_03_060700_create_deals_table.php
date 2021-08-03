<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->date('submission_date');
            $table->unsignedBigInteger('account_id');
            $table->string('name', 100);
            $table->unsignedBigInteger('iso_id');
            $table->enum('sales_stage', ['new deal', 'missing info', 'deal won', 'deal lost']);
            $table->timestamps();

            //$table->primary(['id', 'account_id']);

            $table->index('sales_stage');	

            $table->foreign( 'account_id' )
                    ->references( 'id' )
                    ->on( 'accounts' )
                    ->onDelete( 'cascade' );

            $table->foreign( 'iso_id' )
                    ->references( 'id' )
                    ->on( 'isos' )
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
        Schema::dropIfExists('deals');
    }
}
