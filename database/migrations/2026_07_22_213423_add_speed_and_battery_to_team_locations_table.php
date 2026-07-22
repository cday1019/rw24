<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_locations', function (Blueprint $table) {
            $table->float('speed')->nullable()->after('longitude'); // stored in mph
            $table->integer('battery')->nullable()->after('speed'); // percentage
        });
    }

    public function down(): void
    {
        Schema::table('team_locations', function (Blueprint $table) {
            $table->dropColumn(['speed', 'battery']);
        });
    }
};
