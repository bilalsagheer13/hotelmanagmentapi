<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rooms', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('hotel_id')->nullable()->index('hotel_id');
			$table->string('name')->nullable();
			$table->integer('room_type_id')->nullable()->index('room_type_id');
			$table->integer('room_capacity_id')->nullable()->index('room_capacity_id');
			$table->string('image')->nullable();
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
		Schema::drop('rooms');
	}

}
