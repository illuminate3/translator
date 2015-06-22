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

		$pagelist = $this->getParents($locale_id, null);
		$pagelist = array('' => trans('kotoba::cms.no_parent')) + $pagelist;
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
		$locale_id = 1;
//dd($locales);
//		$pagelist = $this->getParents( $exceptId = $this->id, $locales );

		$pagelist = $this->getParents($locale_id, $id);
		$pagelist = array('' => trans('kotoba::cms.no_parent')) + $pagelist;
//dd($pagelist);

		return compact(
			'content',
			'lang',
			'locales',
			'pagelist'
			);
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
			'is_online'			=> $input['is_online'],
			'order'				=> $input['link'],
			'order'				=> $input['order'],
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

				'slug'			=> $input['slug_'.$properties['id']],

				'meta_title'			=> $input['meta_title_'.$properties['id']],
				'meta_keywords'			=> $input['meta_keywords_'.$properties['id']],
				'meta_description'		=> $input['meta_description_'.$properties['id']]
			];

			$content->update($values);
		}

		$this->manageBaum($input['parent_id']);

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
			'is_online'			=> $input['is_online'],
			'order'				=> $input['link'],
			'order'				=> $input['order'],
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

				'slug'			=> $input['slug_'.$properties['id']],

				'meta_title'			=> $input['meta_title_'.$properties['id']],
				'meta_keywords'			=> $input['meta_keywords_'.$properties['id']],
				'meta_description'		=> $input['meta_description_'.$properties['id']]
			];

			$content->update($values);
		}

		$this->manageBaum($input['parent_id'], $id);

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
	public function getParents($locale_id, $id)
	{
		if ($id != null ) {
			$query = Content::select('content_translations.title AS title', 'contents.id AS id')
				->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
				->where('content_translations.locale_id', '=', $locale_id)
				->where('contents.id', '!=', $id, 'AND')
				->get();
		} else {
			$query = Content::select('content_translations.title AS title', 'contents.id AS id')
			->join('content_translations', 'contents.id', '=', 'content_translations.content_id')
			->where('content_translations.locale_id', '=', $locale_id)
			->get();
		}

		$parents = $query->lists('title', 'id');
//dd($parents);

		return $parents;
	}


	public function manageBaum($parent_id, $id)
	{
//dd($parent_id);

		if ($parent_id != 0 && $id != null) {
			$node = Content::find($id);
			$node->makeChildOf($parent_id);
		}

/*
		if( $inputs['parent_id'] != 0 && $inputs['parent_id'] != $oldPage->parent_id ) {
			$parent = Page::find($inputs['parent_id']);
			$oldPage->makeChildOf($parent);
		}

		if( $inputs['parent_id'] == 0 && $oldPage->parent_id != NULL ) {
			$oldPage->makeRoot();
		}
*/
	}


}
