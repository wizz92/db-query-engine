<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DqeRequestLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('dqe_request_logs')) {
            Schema::create('dqe_request_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('ip', 80);
                $table->string('route', 100);
                $table->text('input');
                $table->text('headers');
                $table->timestamps();
                $table->bigInteger('user_id')->unsigned()->nullable()->default(null);
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('set null')
                    ->onDelete('set null');
            });
        }

        if (Schema::hasTable('custom_query_logs')) {
            Schema::table('custom_query_logs', function (Blueprint $table) {
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
        if (Schema::hasTable('custom_query_logs')) {
            Schema::table('custom_query_logs', function (Blueprint $table) {
                $table->dropForeign('custom_query_logs_request_log_id_foreign');
                $table->dropColumn('request_log_id');
            });
        }
        Schema::dropIfExists('dqe_request_logs');
    }
}
