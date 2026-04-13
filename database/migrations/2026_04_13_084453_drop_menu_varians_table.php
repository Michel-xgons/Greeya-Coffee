<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::dropIfExists('menu_varians');
}

public function down()
{
    Schema::create('menu_varians', function (Blueprint $table) {
        $table->id();
        $table->foreignId('menu_id')->constrained()->onDelete('cascade');
        $table->string('nama_varian');
        $table->integer('harga');
        $table->timestamps();
    });
}
};
