<?php

use App\Models\Common\InvitationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('token');
            $table->enum('status', [
                InvitationStatus::Pending->value,
                InvitationStatus::Approved->value,
                InvitationStatus::Denied->value,
            ]);
            $table->json('role_names');
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestampsTz();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};
