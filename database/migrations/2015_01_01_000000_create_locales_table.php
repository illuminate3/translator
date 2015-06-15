<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

//		'en'          => ['name' => 'English',                'script' => 'Latn', 'native' => 'English'],


		Schema::create('locales', function(Blueprint $table)
		{
			$table->increments('id');
//			$table->string('language', 2);
			$table->string('locale', 2);
			$table->string('name', 20);
			$table->string('script', 20);
			$table->string('native', 20);
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
		Schema::drop('locales');
	}

}
