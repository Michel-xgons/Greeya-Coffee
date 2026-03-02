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
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->string('varian')->nullable()->after('menu_id');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pesanans', function (Blueprint $table) {
            $table->dropColumn('varian');
        });
    }
};
