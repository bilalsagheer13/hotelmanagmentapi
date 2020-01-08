<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRoomPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('room_prices', function(Blueprint $table)
		{
			$table->foreign('hotel_id', 'room_prices_ibfk_1')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('room_type_id', 'room_prices_ibfk_2')->references('id')->on('room_types')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('room_prices', function(Blueprint $table)
		{
			$table->dropForeign('room_prices_ibfk_1');
			$table->dropForeign('room_prices_ibfk_2');
		});
	}

}
