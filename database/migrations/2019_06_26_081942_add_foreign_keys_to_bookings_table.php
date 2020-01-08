<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bookings', function(Blueprint $table)
		{
			$table->foreign('hotel_id', 'bookings_ibfk_1')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('room_id', 'bookings_ibfk_3')->references('id')->on('rooms')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bookings', function(Blueprint $table)
		{
			$table->dropForeign('bookings_ibfk_1');
			$table->dropForeign('bookings_ibfk_3');
		});
	}

}
