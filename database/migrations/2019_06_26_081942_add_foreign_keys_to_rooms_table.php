<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRoomsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rooms', function(Blueprint $table)
		{
			$table->foreign('room_type_id', 'rooms_ibfk_1')->references('id')->on('room_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('room_capacity_id', 'rooms_ibfk_2')->references('id')->on('room_capacities')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('hotel_id', 'rooms_ibfk_3')->references('id')->on('hotels')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rooms', function(Blueprint $table)
		{
			$table->dropForeign('rooms_ibfk_1');
			$table->dropForeign('rooms_ibfk_2');
			$table->dropForeign('rooms_ibfk_3');
		});
	}

}
