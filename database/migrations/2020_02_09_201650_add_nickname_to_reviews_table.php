<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNicknameToReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();
        Schema::table('reviews', function (Blueprint $table) use($driver){
            if ('sqlite' === $driver) {
                $table->dropColumn('order_id');
                $table->string('nickname')->nullable()->after('product_id');
            } else {
                $table->dropForeign('reviews_order_id_foreign');
                $table->dropColumn('order_id');
                $table->string('nickname')->nullable()->after('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('cascade');

            $table->dropColumn('nickname');
        });
    }
}
