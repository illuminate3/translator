<?php namespace App\Http\Controllers;

use App\Article;
use App\Models\Menu;
use App\Models\Repositories\MenuRepository;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Theme;


class MenusController extends Controller {

/*

use Illuminate\Http\Request;
use App\Modules\General\Http\Requests\DeleteRequest;
use App\Modules\General\Http\Requests\MenuCreateRequest;
use App\Modules\General\Http\Requests\MenuUpdateRequest;

use Datatables;
use Flash;
*/

	/**
	 * Menu Repository
	 *
	 * @var Menu
	 */
	protected $menu;

	public function __construct(
			MenuRepository $menu
		)
	{
		$this->menu = $menu;
// middleware
//		$this->middleware('admin');
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$menus = $this->menu->all();
//dd($locales);

		return Theme::View('menus.index', compact('menus', 'locales'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return Theme::View('menus.create',  $this->menu->create());
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(
		MenuCreateRequest $request
		)
	{
//dd($request);

		$this->menu->store($request->all());

		Flash::success( trans('kotoba::cms.success.menu_create') );
		return redirect('admin/menus');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
// 		$menu = $this->menu->findOrFail($id);
//
// 		return View::make('HR::menus.show', compact('menu'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$modal_title = trans('kotoba::general.command.delete');
		$modal_body = trans('kotoba::general.ask.delete');
		$modal_route = 'admin.menus.destroy';
		$modal_id = $id;
//		$model = '$menu';
		$model = 'menu';
//dd($model);

		return View('general::menus.edit',
//		return Theme::View('menus.edit',
			$this->menu->edit($id),
				compact(
					'modal_title',
					'modal_body',
					'modal_route',
					'modal_id',
					'model'
			));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(
		MenuUpdateRequest $request,
		$id
		)
	{
//dd($request);

		$this->menu->update($request->all(), $id);

		Flash::success( trans('kotoba::cms.success.menu_update') );
		return redirect('admin/menus');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->menu->find($id)->delete();

		return Redirect::route('admin.menus.index');
	}

	/**
	* Datatables data
	*
	* @return Datatables JSON
	*/
	public function data()
	{
//		$query = Menu::select(array('menus.id','menus.name','menus.description'))
//			->orderBy('menus.name', 'ASC');
//		$query = Menu::select('id', 'name' 'description', 'updated_at');
//			->orderBy('name', 'ASC');
		$query = Menu::select('id', 'name', 'description', 'updated_at');
//dd($query);

		return Datatables::of($query)
//			->remove_column('id')

			->addColumn(
				'actions',
				'
					<a href="{{ URL::to(\'admin/menus/\' . $id . \'/edit\' ) }}" class="btn btn-success btn-sm" >
						<span class="glyphicon glyphicon-pencil"></span>  {{ trans("kotoba::button.edit") }}
					</a>
				'
				)

			->make(true);
	}


}
