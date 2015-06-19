<?php

use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

	public function up()
	{
	    Schema::create('pages', function($table) 
	    {
	      	$table->increments('id');
	      	$table->integer('user_id')->unsigned();
	      	$table->integer('parent_id')->nullable();
	      	$table->integer('lft')->nullable();
	      	$table->integer('rgt')->nullable();
	      	$table->integer('depth')->nullable();
	      	$table->string('title', 255);
	      	$table->string('slug', 255);
			$table->string('summary',512);
			$table->text('content');
			$table->text('featured_image', 255)->nullable();
			$table->text('link', 255)->nullable();
			$table->integer('order');
			$table->integer('version');
			$table->boolean('is_online')->default(0);
			$table->boolean('is_current')->default(0);
			$table->boolean('is_latest')->default(1);
			$table->boolean('is_deleted')->default(0);
	      	$table->timestamps();

	      	$table->index('parent_id');
	      	$table->index('lft');
	      	$table->index('rgt');
	      	$table->index('slug');

	      	$table->engine = "InnoDB";
	      	$table->foreign('user_id')->references('id')->on('users');
	    });
	}

	public function down()
	{
		Schema::drop('pages');
	}

}