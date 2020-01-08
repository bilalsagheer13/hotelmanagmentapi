<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_prices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('hotel_id')->nullable()->index('hotel_id');
			$table->float('price', 10, 0)->nullable();
			$table->integer('room_type_id')->nullable()->index('room_type_id');
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('room_prices');
	}

}
