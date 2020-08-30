<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->autoIncrement()->unsigned();
            $table->integer('user_id')->length(20)->unsigned();
            $table->string('post_id')->length(20);
            $table->string('group_id')->length(30);
            $table->text('title');
            $table->longText('text');
            $table->text('attachments');
            $table->text('attachments_others');
            $table->longText('attachments_urls');
            $table->string('post_link', 100);
            $table->enum('link', ['0', '1']);
            $table->enum('ad', ['0', '1']);
            $table->enum('sign', ['0', '1']);
            $table->enum('disable_comments', ['0', '1']);
            $table->string('every', 15);
            $table->dateTime('UTC_start', 0);
            $table->dateTime('start', 0);
            $table->enum('status', ['0', '1']);
            $table->text('error');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
