<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenusTables extends Migration
{

	public function __construct()
	{
		// Get the prefix
		$this->prefix = Config::get('general.general_db.prefix', '');
	}

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create($this->prefix . 'menus', function(Blueprint $table) {

			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

			$table->string('name');
			$table->string('class')->nullable();

			$table->softDeletes();
			$table->timestamps();

		});

		Schema::create($this->prefix . 'menu_translations', function(Blueprint $table) {

// 			$table->string('title');
// 			$table->string('content');

// 			$table->integer('article_id')->unsigned()->index();
// 			$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
//
// 			$table->integer('locale_id')->unsigned()->index();
// 			$table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
//
// 			$table->unique(['article_id', 'locale_id']);
//
// 			$table->timestamps();



			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();

//			$table->integer('menu_id')->unsigned();
//			$table->string('locale')->index();
			$table->boolean('status')->default(0);
			$table->string('title');

//			$table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

			$table->integer('menu_id')->unsigned()->index();
			$table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

			$table->integer('locale_id')->unsigned()->index();
			$table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');

			$table->unique(['menu_id', 'locale_id']);

			$table->softDeletes();
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
		Schema::drop($this->prefix . 'menu_translations');
		Schema::drop($this->prefix . 'menus');
	}


}
