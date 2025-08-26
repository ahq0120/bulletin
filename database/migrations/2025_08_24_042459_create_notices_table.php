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
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);                 // 標題
            $table->string('author', 100)->default('Administrator'); // 公布者（固定）
            $table->date('published_at');                 // 發佈日期
            $table->date('due_date')->nullable();         // 截止日期（可空）
            $table->longText('content')->nullable();      // 公布內容（含 CKEditor HTML）
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
