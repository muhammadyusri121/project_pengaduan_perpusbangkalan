<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('complaint_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // petugas yang merespon
            $table->text('message');
            $table->enum('status_change', ['pending', 'in_progress', 'resolved', 'rejected'])->nullable();
            $table->boolean('is_public')->default(true); // apakah visible ke pelapor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('complaint_responses');
    }
}
