<?php

use Illuminate\Database\Seeder;
use App\Models\Locale;

use Config;
//use Eloquent;
//use Model;
//use Schema;

class NavItemsTableSeeder extends Seeder {

	public function run()
	{
		// Uncomment the below to wipe the table clean before populating
		DB::table('fbf_nav_items')->delete();

		$types = Config::get('laravel-navigation::types');
		$roots = array_keys($types);
		foreach ($roots as $root)
		{
			NavItem::create(array(
				'title' => $root,
			));
		}

		// Uncomment the below to run the seeder
//		DB::table('locales')->insert($seeds);
	}

}
