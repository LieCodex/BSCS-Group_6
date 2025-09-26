<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');     // Who receives notification
        $table->unsignedBigInteger('actor_id')->nullable(); // Who triggered it
        $table->unsignedBigInteger('post_id')->nullable();
        $table->unsignedBigInteger('comment_id')->nullable();
        $table->enum('type', ['like_post', 'like_comment', 'comment', 'reply', 'milestone']);
        $table->string('preview_text', 255)->nullable();
        $table->boolean('is_seen')->default(false);
        $table->timestamps();

        // Foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('actor_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
