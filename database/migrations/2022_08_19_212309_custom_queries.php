<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomQueries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('custom_queries')) {
            Schema::create('custom_queries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50)->unique();
                $table->text('query');
                $table->text('params')->nullable();
                $table->smallInteger('type')->unsigned();
                $table->smallInteger('status')->unsigned();
                $table->timestamps();
                $table->text('description')->nullable();
                $table->integer('created_by')->unsigned()->nullable()->default(null);
                $table->integer('moderated_by')->unsigned()->nullable()->default(null);
            });
        }
        if (!Schema::hasTable('custom_query_logs')) {
            Schema::create('custom_query_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('custom_query_id')->unsigned();
                $table->float('execution_time')->nullable();
                $table->text('query');
                $table->text('results');
                $table->smallInteger('status')->unsigned();
                $table->timestamps();
                $table->foreign('custom_query_id')
                    ->references('id')
                    ->on('custom_queries')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->integer('request_log_id')->unsigned()->nullable()->default(null);
                $table->foreign('request_log_id')
                    ->references('id')
                    ->on('dqe_request_logs')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_query_logs');
        Schema::dropIfExists('custom_queries');
    }
}
