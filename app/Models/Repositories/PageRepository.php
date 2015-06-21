<?php
namespace App\Models\Repositories;

use App\Models\Locale;
use App\Models\Nifty\Page;
use Illuminate\Support\Collection;

use App;
use DB;
use Session;
//use Hash, DB, Auth;
//use DateTime;
//use File, Auth;

class PageRepository extends BaseRepository {

	/**
	 * The Module instance.
	 *
	 * @var App\Modules\ModuleManager\Http\Domain\Models\Module
	 */
	protected $page;

	/**
	 * Create a new ModuleRepository instance.
	 *
   	 * @param  App\Modules\ModuleManager\Http\Domain\Models\Module $module
	 * @return void
	 */
	public function __construct(
		Page $page
		)
	{
		$this->model = $page;
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
//dd($locales);

		return compact('locales', 'lang');
	}

	/**
	 * Get user collection.
	 *
	 * @param  string  $slug
	 * @return Illuminate\Support\Collection
	 */
	public function show($id)
	{
		$page = $this->model->find($id);
		$links = Page::find($id)->pagelinks;
//$page = $this->page->show($id);

//$page = $this->model->where('id', $id)->first();
//		$page = new Collection($page);
//dd($page);

		return compact('page', 'links');
	}

	/**
	 * Get user collection.
	 *
	 * @param  int  $id
	 * @return Illuminate\Support\Collection
	 */
	public function edit($id)
	{
		$page = $this->model->find($id);
		$lang = Session::get('locale');
		$locales = $this->getLocales();
//dd($page);

		return compact('page', 'locales', 'lang');
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
			'name'			=> $input['name'],
			'class'			=> $input['class']
		];

		$page = Page::create($values);

		$locales = $this->getLocales();

		foreach($locales as $locale => $properties)
		{
			App::setLocale($properties['locale']);

			if ( !isset($input['status_'.$properties['id']]) ) {
				$status = 0;
			} else {
				$status = $input['status_'.$properties['id']];
			}

			$values = [
				'status'	=> $status,
				'title'		=> $input['title_'.$properties['id']]
			];

			$page->update($values);
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

		$page = Page::find($id);

		$values = [
			'name'			=> $input['name'],
			'class'			=> $input['class']
		];

		$page->update($values);

		$locales = $this->getLocales();

		foreach($locales as $locale => $properties)
		{
			App::setLocale($properties['locale']);

			$values = [
				'status'	=> $input['status_'.$properties['id']],
				'title'		=> $input['title_'.$properties['id']]
			];

			$page->update($values);
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

	if ( empty($locales) ) {
		throw new LocalesNotDefinedException('Please make sure you have run "php artisan config:publish dimsav/laravel-translatable" ' . ' and that the locales configuration is defined.');
	}

	return $locales;
	}


	public function getPageID($name)
	{

		$id = DB::table('pages')
			->where('name', '=', $name)
			->pluck('id');

		return $id;
	}


}
