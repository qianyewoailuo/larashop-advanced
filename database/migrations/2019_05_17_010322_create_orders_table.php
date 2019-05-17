<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no')->unique()->comment('订单流水号');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->text('address')->comment('json格式的收货地址');
            $table->decimal('total_amount')->comment('订单总价格');
            $table->text('remark')->nullable()->comment('订单备注');
            $table->dateTime('paid_at')->nullable()->comment('支付时间');
            $table->string('payment_method')->nullable()->comment('支付方式');
            $table->string('payment_no')->nullable()->comment('支付平台订单号');
            // 退款状态默认未退款
            $table->string('refund_status')
                ->default(\App\Models\Order::REFUND_STATUS_PENDING);
            $table->string('refund_no')->nullable()->comment('退款单号');   // 唯一?
            $table->boolean('closed')->default(false)->comment('订单是否已关闭');
            $table->boolean('reviewed')->default(false)->comment('是否已评价');
            // 物流状态默认未发货
            $table->string('ship_status')
                ->default(\App\Models\Order::SHIP_STATUS_PENDING);
            $table->text('ship_data')->nullable()->comment('物流数据');
            $table->text('extra')->nullable()->comment('其他额外数据');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
