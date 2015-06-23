<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// use Laracasts\Presenter\PresentableTrait;
// use Vinkla\Translator\Translatable;
// use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

use Baum\Node;
use Cache;
use DB;


class Content extends Node {
//class Content extends Node implements TranslatableContract {
//class Content extends Model implements TranslatableContract {

// 	use Translatable;
// 	use PresentableTrait;

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
		'is_deleted',
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

	public static function getRoots()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			return static::whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
// 							->where('slug', '<>', 'home-page')
// 							->where('slug', '<>', 'search')
// 							->where('slug', '<>', 'terms-conditions')
							->orderBy('order')
							->get();
		// });

		// return $roots;
	}


	public static function getStaticRoots($locale_id)
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
		$page = DB::table('contents')
			->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
			->where('content_translations.locale_id', '=', $locale_id)
			->where('contents.is_online', '=', 1, 'AND')
			->where('contents.is_deleted', '=', 0, 'AND')
			->where('contents.parent_id', '=', null, 'AND')
//			->where('content_translations.slug', '=', $slug, 'AND')
//			->first();
			->orderBy('order')
			->get();
//dd($page);
		return $page;
	}

	public static function getRootsStatic()
	{
		// $roots = Cache::rememberForever('roots', function()
		// {
			return static::join('content_translations', 'contents.id', '=', 'content_translations.content_id')
							->whereIsCurrent(1)
							->whereIsOnline(1)
							->whereIsDeleted(0)
							->whereParentId(NULL)
//			->where('content_translations.locale_id', '=', $locale_id)
// 							->where('slug', '<>', 'home-page')
// 							->where('slug', '<>', 'search')
// 							->where('slug', '<>', 'terms-conditions')
							->orderBy('order')
							->get();
		// });

		// return $roots;
	}


	public static function getParentOptions($exceptId)
	{
//dd($exceptId);
dd(['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->lists('title', 'id'));

		return $exceptId
			? ['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->whereNotIn('id', [$exceptId])
				->lists('title', 'id')
			: ['0' => trans('kotoba::cms.no_parent')]
				+ static::whereIsDeleted(0)
				->lists('title', 'id');
	}


	public static function getPage( $slug )
	{
	   $page =  static::whereIsCurrent(1)
					   ->whereIsOnline(1)
					   ->whereIsDeleted(0)
					   ->whereSlug($slug)
					   ->first();

		return $page;
	}


}
