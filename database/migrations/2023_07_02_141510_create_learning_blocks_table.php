<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearningBlocksTable extends Migration
{
    public function up()
    {
        Schema::create('learning_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->references('id')->on('topics')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->integer('order');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('learning_blocks', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
        });

        Schema::dropIfExists('learning_blocks');
    }
}
