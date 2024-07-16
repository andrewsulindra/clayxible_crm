<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('owner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();

            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile_phone')->nullable();

            $table->text('notes')->nullable();
            
            $table->bigInteger('group_id');
            $table->tinyInteger('is_active')->default(1)->comment('0: no, 1: yes');

            $table->integer('owner_category_id')->unsigned()->nullable();

            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('owner');
    }
}
