<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('zip_code');
            $table->unsignedBigInteger('introducer_id')->nullable()->after('commission_percentage');
            $table->decimal('commission_amount', 15, 2)->nullable()->after('introducer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('commission_percentage');
            $table->dropColumn('introducer_id');
            $table->dropColumn('commission_amount');
        });
    }
};
