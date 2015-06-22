<?php
namespace App\Models\Repositories;

use App\Models\Locale;
use App\Models\Content;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

use App;
use DB;
use Route;
use Session;

class ContentRepository extends BaseRepository {

	/**
	 * The Module instance.
	 *
	 * @var App\Modules\ModuleManager\Http\Domain\Models\Module
	 */
	protected $content;

	/**
	 * Create a new ModuleRepository instance.
	 *
   	 * @param  App\Modules\ModuleManager\Http\Domain\Models\Module $module
	 * @return void
	 */
	public function __construct(
		Content $content
		)
	{
		$this->model = $content;

		$this->id = Route::current()->parameter( 'id' );
//		$this->pagelist = Page::getParentOptions( $exceptId = $this->id );
//		$this->pagelist = Content::getParentOptions( $exceptId = $this->id );
//dd($this->pagelist);
	}

	/**
	 * Get role collection.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function create()
	{
		$lang = Session::get('locale');
		$locales = $this->getLocales();
		$locale_id = 1;
//dd($locales);
//		$pagelist = $this->getParents( $exceptId = $this->id, $locales );
		$pagelist = $this->getParents($locale_id);
		$pagelist = $pagelist->lists('title', 'id');
//dd($pagelist);

		return compact(
			'lang',
			'locales',
			'pagelist'
			);
	}

	/**
	 * Get user collection.
	 *
	 * @param  string  $slug
	 * @return Illuminate\Support\Collection
	 */
	public function show($id)
	{
		$content = $this->model->find($id);
		$links = Content::find($id)->contentlinks;
//$content = $this->content->show($id);

//$content = $this->model->where('id', $id)->first();
//		$content = new Collection($content);
//dd($content);

		return compact('content', 'links');
	}

	/**
	 * Get user collection.
	 *
	 * @param  int  $id
	 * @return Illuminate\Support\Collection
	 */
	public function edit($id)
	{
		$content = $this->model->find($id);
		$lang = Session::get('locale');
		$locales = $this->getLocales();
//dd($content);

		return compact('content', 'locales', 'lang');
	}

	/**
	 * Get all models.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function store($input)
	{
//dd($input);

		$values = [
//			'name'			=> $input['name'],
			'is_online'			=> 1,
			'order'				=> 1,
			'user_id'			=> 1
		];
//dd($values);

		$content = Content::create($values);

		$locales = $this->getLocales();

		foreach($locales as $locale => $properties)
		{
			App::setLocale($properties['locale']);

/*
			if ( !isset($input['status_'.$properties['id']]) ) {
				$status = 0;
			} else {
				$status = $input['status_'.$properties['id']];
			}
*/

			$values = [
				'content'		=> $input['content_'.$properties['id']],
				'summary'		=> $input['summary_'.$properties['id']],
				'title'			=> $input['title_'.$properties['id']],

				'meta_title'			=> $input['meta_title_'.$properties['id']],
				'meta_keywords'			=> $input['meta_keywords_'.$properties['id']],
				'meta_description'		=> $input['meta_description_'.$properties['id']]
			];

			$content->update($values);
		}

		App::setLocale('en');
		return;

	}

	/**
	 * Update a role.
	 *
	 * @param  array  $inputs
	 * @param  int    $id
	 * @return void
	 */
	public function update($input, $id)
	{
//dd($input);

		$content = Content::find($id);

		$values = [
//			'name'			=> $input['name'],
			'is_online'			=> 1,
			'order'				=> 1,
			'user_id'			=> 1
		];

		$content->update($values);

		$locales = $this->getLocales();

		foreach($locales as $locale => $properties)
		{
			App::setLocale($properties['locale']);

			$values = [
				'content'		=> $input['content_'.$properties['id']],
				'summary'		=> $input['summary_'.$properties['id']],
				'title'			=> $input['title_'.$properties['id']],

				'meta_title'			=> $input['meta_title_'.$properties['id']],
				'meta_keywords'			=> $input['meta_keywords_'.$properties['id']],
				'meta_description'		=> $input['meta_description_'.$properties['id']]
			];

			$content->update($values);
		}

		App::setLocale('en');
		return;
	}


	public function getLocales()
	{

// 		$config = App::make('config');
// 		$locales = (array) $config->get('languages.supportedLocales', []);
 		$locales = Locale::all();
// 		$locales = DB::table('locales')
// 			->lists('locale');

//dd($locales);

	return $locales;
	}


	public function getContentID($name)
	{

		$id = DB::table('contents')
			->where('name', '=', $name)
			->pluck('id');

		return $id;
	}

//	public function getParents($exceptId, $locale)
	public function getParents($locale_id)
	{
		$query = $this->model
//		->join('menulink_translations', 'menulinks.id', '=', 'menulink_translations.menulink_id')
		->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
		->where('content_translations.locale_id', '=', $locale_id)
//		->where('contents.id', '!=', $exceptId, 'AND')
		->where('contents.is_deleted', '=', 0, 'AND');
//		->orderBy('menulinks.position');

		$models = $query->get();
//dd($models);

		return $models;
	}


}