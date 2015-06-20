<?php

use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	public function up()
	{
		Schema::create('settings', function($table) {
			$table->increments('id');
			$table->string('sitename', 255);
			$table->integer('maximum_versions');
			$table->timestamps();
		});

		$date_now = date('Y-m-d H:i:s');

		$data = [
				    [
			        	'sitename'    => 'Nifty',
			        	'maximum_versions' => 10,
			        	'created_at' => $date_now,
			        	'updated_at' => $date_now
	    			]    
				];

		DB::table('settings')->insert($data);		
	}

	public function down()
	{
		Schema::drop('settings');
	}

}