<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('organization_code')->nullable();
            $table->string('nip')->nullable();
            $table->string('username')->nullable();
            $table->jsonb('jabatan_struktural_organisasi')->nullable();
            $table->jsonb('plt_jabatan_struktural_organisasi')->nullable();
            $table->jsonb('plh_jabatan_struktural_organisasi')->nullable();
            $table->boolean('active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('organization_id');
            $table->dropColumn('nip');
            $table->dropColumn('username');
            $table->dropColumn('jabatan_struktural_organisasi');
            $table->dropColumn('plt_jabatan_struktural_organisasi');
            $table->dropColumn('plh_jabatan_struktural_organisasi');
            $table->dropColumn('active');
        });
    }
};
