<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->integer('user_id')->length(20)->unsigned();
            $table->string('post_id')->length(30);
            $table->string('parent_group_id')->length(20);
            $table->longText('text');
            $table->text('attachments');
            $table->text('attachments_others');
            $table->longText('attachments_urls');
            $table->string('post_link', 100);
            $table->dateTime('datetime', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
