<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {

            if (Schema::hasColumn('pesanans', 'user_id')) {
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('pesanans', 'processed_at')) {
                $table->dropColumn('processed_at');
            }

            if (Schema::hasColumn('pesanans', 'completed_at')) {
                $table->dropColumn('completed_at');
            }

        });
    }

    public function down(): void
    {
        Schema::table('pesanans', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
        });
    }
};

