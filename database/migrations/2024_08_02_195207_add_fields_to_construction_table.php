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
        Schema::table('constructions', function (Blueprint $table) {
            // Adding the fields
            $table->unsignedBigInteger('contact_id')->nullable()->after('end_date');
            $table->unsignedBigInteger('introducer_id')->nullable()->after('contact_id');
            $table->decimal('commission_percentage', 5, 2)->nullable()->after('introducer_id');
            $table->decimal('total_payment', 15, 2)->nullable()->after('commission_percentage');
            $table->decimal('advance_payment', 15, 2)->nullable()->after('total_payment');

            // Adding foreign key constraints
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('introducer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constructions', function (Blueprint $table) {
            // Dropping the foreign key constraints
            $table->dropForeign(['contact_id']);
            $table->dropForeign(['introducer_id']);

            // Dropping the columns
            $table->dropColumn('contact_id');
            $table->dropColumn('introducer_id');
            $table->dropColumn('commission_percentage');
            $table->dropColumn('total_payment');
            $table->dropColumn('advance_payment');
        });
    }
};
