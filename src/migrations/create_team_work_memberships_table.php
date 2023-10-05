<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamWorkMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_work_memberships', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('team_work_code');
            $table->string('nip');
            $table->integer('type');
            $table->boolean('active')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_work_memberships');
    }
};
