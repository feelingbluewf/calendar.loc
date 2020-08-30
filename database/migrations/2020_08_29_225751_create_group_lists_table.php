<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_lists', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->integer('user_id')->length(20)->unsigned();
            $table->string('group_id')->length(20);
            $table->string('unique_id')->length(30);
            $table->text('group_name');
            $table->text('group_screen_name');
            $table->string('parent_group_id')->length(20);
            $table->text('parent_group_name');
            $table->enum('status', ['0', '1']);
            $table->integer('quantity')->length(20)->unsigned();
            $table->text('error');
            $table->text('avatar');
            $table->timestamp('timestamp', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_lists');
    }
}
