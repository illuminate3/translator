<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laracasts\Presenter\PresentableTrait;
use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

use Baum\Node;
use Cache;

class Content extends Node implements TranslatableContract {
//class Content extends Model implements TranslatableContract {

	use Translatable;
	use PresentableTrait;

	protected $table = 'contents';

// Presenter -------------------------------------------------------
	protected $presenter = 'App\Http\Presenters\General';


// Translation Model -------------------------------------------------------
	protected $translator = 'App\Models\ContentTranslation';


// DEFINE Hidden -------------------------------------------------------
	protected $hidden = [
		'created_at',
		'updated_at'
		];


// DEFINE Fillable -------------------------------------------------------
	protected $fillable = [
		'is_online',
		'order',
		'user_id',
		// Translatable columns
		'meta_description',
		'meta_keywords',
		'meta_title',
		'content',
		'slug',
		'summary',
		'title'
		];


// Translated Columns -------------------------------------------------------
	protected $translatedAttributes = [
		'meta_description',
		'meta_keywords',
		'meta_title',
		'content',
		'slug',
		'summary',
		'title'
		];

// 	protected $appends = [
// 		'status',
// 		'title'
// 		];

	public function getContentAttribute()
	{
		return $this->content;
	}

	public function getSummaryAttribute()
	{
		return $this->summary;
	}

	public function getTitleAttribute()
	{
		return $this->title;
	}


}
