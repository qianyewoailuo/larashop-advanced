<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCouponCodeIdToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('coupon_code_id')->nullable()->after('paid_at');
            $table->foreign('coupon_code_id')->references('id')->on('coupon_codes')->onDelete('set null');
            /**
             * onUpdate() | onDelete() 可以设置 4 种参数
             * no action | set null | set default | cascade
             * cascade 表示级联操作，就是说，如果主键表中被参考字段更新，外键表中也更新，主键表中的记录被删除，外键表中改行也相应删除
             * set null 表示在外键表中将相应字段设置为null
             * set default 表示设置为默认值
             * no action 表示 不做任何操作
             */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['coupon_code_id']);
            $table->dropColumn('coupon_code_id');
        });
    }
}
