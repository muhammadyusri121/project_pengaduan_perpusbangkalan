<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code', 10)->unique();
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // pelapor
            $table->string('reporter_name');
            $table->string('reporter_phone', 20);
            $table->string('reporter_email')->nullable();
            $table->string('subject');
            $table->text('description');
            $table->string('location')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->boolean('is_anonymous')->default(false);
            $table->text('rejection_reason')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
