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
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('construction_id')->unsigned()->nullable()->after('created_by');
            $table->foreign('construction_id')->references('id')->on('constructions')->onDelete('cascade');

            // Indexing the new column
            $table->index('construction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['construction_id']);
            $table->dropIndex(['construction_id']);
            $table->dropColumn('construction_id');
        });
    }
};
