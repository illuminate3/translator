<?php
namespace App\Http\Controllers;

use App\Models\Nifty\Page;
//use App\Models\Repositories\PageRepository;
use App\Models\Nifty\Setting;

use App\Helpers\Nifty\BackendPages;

use Illuminate\Http\Request;
use App\Http\Requests\DeleteRequest;
// use App\Http\Requests\PageCreateRequest;
// use App\Http\Requests\PageUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Cache;
use Carbon\Carbon;
//use Datatables;
use Flash;
use Route;
use Theme;
use View;


class PageController extends Controller {

	public function __construct()
	{
//		$this->user = Sentry::getUser();
		$this->user = "1";
//		$this->isAdmin = User::isAdmin( $this->user );
		$this->isAdmin = true;
//		$this->logged_in_for = $this->user->last_login->diffForHumans();
		$this->logged_in_for = "1 hour ago";

		$this->configs = Setting::getSiteSettings();

		$this->id = Route::current()->parameter( 'id' );
		$this->pagelist = Page::getParentOptions( $exceptId = $this->id );
		$this->rules = Page::$rules;
		$this->activeParent = 'pages';
		$this->paginate = 100;
		$this->cacheMinutes = 30;
		$this->thumbnailPath = Setting::getThumbnailPath();
	}

	public function index()
	{
		$pages = Page::getLatestVersions( 'allNotDeleted', $this->paginate );

		$backendPages = new BackendPages( $pages, $type = 'all' );
		$pagesHtml = $backendPages->getPagesHtml();

		$allNotDeletedNum = Page::getNotDeletedPagesNum( $this->cacheMinutes );
		$publishedNum  = Page::getPublishedPagesNum( $this->cacheMinutes );
		$draftsNum = Page::getDraftPagesNum( $this->cacheMinutes );
		$deletedNum = Page::getDeletedPagesNum( $this->cacheMinutes );

		$nums = compact("allNotDeletedNum", "publishedNum", "draftsNum", "deletedNum");

		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$configs = $this->configs;
		$logged_in_for = $this->logged_in_for;
		$pagesHtml = $pagesHtml;
		$nums = $nums;
		$type = 'All';
		$activeParent = $this->activeParent;
		$active = 'allpages';
//		$links = $pages->links('nifty.backend.pagination.nifty');

		return View('nifty.backend.pages.index', compact(
			'user',
			'isAdmin',
			'configs',
			'logged_in_for',
			'pagesHtml',
			'nums',
			'type',
			'activeParent',
			'active'
//			'links'
			));

	}


	public function create()
	{
		$pagelist = $this->pagelist;
		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$logged_in_for = $this->logged_in_for;
		$activeParent = $this->activeParent;
		$active = 'createpage';
		$configs = $this->configs;
		$thumbnailPath = asset($this->thumbnailPath);

		return View('nifty.backend.pages.new', compact(
			'pagelist',
			'user',
			'isAdmin',
			'logged_in_for',
			'activeParent',
			'active',
			'configs',
			'thumbnailPath'
			));

	}

	public function store()
	{
		$inputs = [];
		foreach(Input::all() as $key=>$input)
		{
			$inputs[$key] = Sanitiser::trimInput($input);
		}

		$validation = MyValidations::validate($inputs, $this->rules);

		if($validation != NULL)
		{
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else
		{
			$existingSlugs = Page::lists('slug');
			$slug = MyValidations::makeSlug($existingSlugs, Str::slug($inputs['title']));

			$order = $inputs['order'];

			if ( strlen($order) == 0 )
				$order = 0;

			$is_current = $inputs['is_online'] == 1 ? 1 : 0;
			$link = Input::get('link') ? Input::get('link') : NULL;
			$featured_image = Input::get('featured_image') ? Input::get('featured_image') : NULL;

			$pageArr = [
					'user_id' => $this->user->id,
					'title' => $inputs['title'],
					'slug' => $slug,
					'summary' => $inputs['summary'],
					'content' => $inputs['content'],
					'link' => $link,
					'featured_image' => $featured_image,
					'order' => $order,
					'version' => 1,
					'is_online' => $inputs['is_online'],
					'is_current' => $is_current
				];

			$page = Page::create($pageArr);

			if($inputs['parent_id'] != 0)
			{
				$parent = Page::find($inputs['parent_id']);
				$page->makeChildOf($parent);
			}

			Cache::flush();
			return Redirect::to('dashboard/pages')->withSuccess('New page created.');
		}

	}


	public function edit($id)
	{
		$page = Page::find($id);

		$page = $page;
		$pagelist = $this->pagelist;
		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$logged_in_for = $this->logged_in_for;
		$activeParent = $this->activeParent;
		$active = 'allpages';
		$configs = $this->configs;
		$thumbnailPath = asset($this->thumbnailPath);

		return View('nifty.backend.pages.edit', compact(
			'page',
			'pagelist',
			'user',
			'isAdmin',
			'logged_in_for',
			'activeParent',
			'active',
			'configs',
			'thumbnailPath'
			));

	}


	public function update($id)
	{
		$inputs = [];
		foreach(Input::all() as $key=>$input) {
			$inputs[$key] = Sanitiser::trimInput($input);
		}

		$validation = MyValidations::validate($inputs, $this->rules);

		if( $validation != NULL ) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {
			$oldPage = Page::find($id);
			$order = $inputs['order'];

			if( strlen($order) == 0 )
				$order = 0;

			if ( $oldPage->is_online ) {
				$oldPage->is_latest = 0;
				$oldPage->is_current = $inputs['is_online'] == 1 ? 0 : 1;
				$oldPage->save();

				$existingVersions = Page::where('slug', $oldPage->slug)->lists('version');

				$version = MyValidations::assignVersion($existingVersions, $oldPage->version + 1);

				$link = Input::get('link') ? Input::get('link') : NULL;
				$featured_image = Input::get('featured_image') ? Input::get('featured_image') : NULL;

				$pageArr = [
						'user_id' => $this->user->id,
						'title' => $inputs['title'],
						'slug' => $oldPage->slug,
						'summary' => $inputs['summary'],
						'content' => $inputs['content'],
						'link' => $link,
						'featured_image' => $featured_image,
						'order' => $order,
						'version' => $version,
						'is_latest' => 1,
						'is_online' => $inputs['is_online'],
						'is_current' => $inputs['is_online'] == 1 ? 1 : 0
					];

				$newPage = Page::create($pageArr);

				if( $inputs['parent_id'] != 0 ) {
					$parent = Page::find($inputs['parent_id']);
					$newPage->makeChildOf($parent);
				}

				$oldPageChildrenNum = count( $oldPage->getImmediateDescendants() );

				for ($i=0; $i < $oldPageChildrenNum; $i++) {
					$oldPage->getImmediateDescendants()[0]->makeChildOf( $newPage );
				}

			}

			else {
				$oldPage->user_id = $this->user->id;
				$oldPage->title = $inputs['title'];
				$oldPage->summary = $inputs['summary'];
				$oldPage->content = $inputs['content'];
				$oldPage->featured_image = Input::get('featured_image') ? Input::get('featured_image') : NULL;
				$oldPage->order = $order;
				$oldPage->is_online = $inputs['is_online'];
				$oldPage->is_current = $inputs['is_online'] == 1 ? 1 : 0;
				$oldPage->save();

				if ( $inputs['is_online'] == 1 ) {

					$versions = Page::whereSlug($oldPage->slug)->whereNotIn('id', [$oldPage->id])->get();
					foreach ( $versions as $version ) {
						if ( $version->id != $oldPage->id ) {
							$version->is_current = 0;
							$version->is_latest = 0;
							$version->save();
						}
					}
				}

				if( $inputs['parent_id'] != 0 && $inputs['parent_id'] != $oldPage->parent_id ) {
					$parent = Page::find($inputs['parent_id']);
					$oldPage->makeChildOf($parent);
				}

				if( $inputs['parent_id'] == 0 && $oldPage->parent_id != NULL ) {
					$oldPage->makeRoot();
				}

			}

			return Redirect::to('dashboard/pages')->withSuccess('Page successfully updated.');
		}

	}


	public function destroy($id)
	{
		$page = Page::find( $id );

		$allDescendants = $page->getDescendants();
		$nodeDeletion = new NodeDeletion( $page, $allDescendants );
		$destroyed = 1 + $nodeDeletion->destroyAllDescendants();
		$page->delete();

		Cache::flush();
		return Redirect::back()->withSuccess($destroyed . ' ' . str_plural('page', $destroyed) . ' permanently deleted.');
	}

	public function bulk_destroy()
	{
		$pageIds = Input::get('pages');
		$destroyed = 0;

		foreach ( $pageIds as $pageId ) {
			$page = Page::find( $pageId );
			if ( $page ) {
				$allDescendants = $page->getDescendants();
				$nodeDeletion = new NodeDeletion( $page, $allDescendants );
				$destroyed = $destroyed + 1 + $nodeDeletion->destroyAllDescendants();
			}
		}

		Page::whereIn('id', $pageIds)->delete();

		Cache::flush();
		return Redirect::back()->withSuccess($destroyed . ' ' . str_plural('page', $destroyed) . ' permanently deleted.');
	}

	public function delete($id)
	{
		$page = Page::find($id);
		$deleted = 0;

		if ( $page ) {
			$allDescendants = $page->getDescendants();
			$nodeDeletion = new NodeDeletion( $page, $allDescendants );
			$deleted = $deleted + 1 + $nodeDeletion->softDeleteAllDescendants();
			$page->is_deleted = 1;
			$page->save();
		}

		Cache::flush();
		return Redirect::back()->withSuccess($deleted . ' ' . str_plural('page', $deleted) . ' moved to trash.');
	}

	public function bulk_delete()
	{
		$pageIds = Input::get('pages');
		$deleted = 0;

		foreach ( $pageIds as $pageId ) {
			$page = Page::find($pageId);
			if ( $page ) {
				$allDescendants = $page->getDescendants();
				$nodeDeletion = new NodeDeletion( $page, $allDescendants );
				$deleted = $deleted + 1 + $nodeDeletion->softDeleteAllDescendants();
				$page->is_deleted = 1;
				$page->save();
			}
		}

		Cache::flush();
		return Redirect::back()->withSuccess($deleted . ' ' . str_plural('page', $deleted) . ' moved to trash.');
	}

	public function bulk_publish()
	{
		$pageIds = Input::get('pages');
		foreach ($pageIds as $pageId) {
			$page = Page::findOrFail($pageId);

			$oldPages = Page::whereSlug($page->slug)->get();

			foreach ($oldPages as $oldPage) {
				if ( $oldPage->id != $page->id ) {
					$oldPage->is_current = 0;
					$oldPage->is_latest = 0;
					$oldPage->save();
				}
			}

			$page->is_online = 1;
			$page->is_current = 1;
			$page->is_latest = 1;
			$page->save();
		}

		Cache::flush();
		return Redirect::back()->withSuccess(count($pageIds) . ' ' . str_plural('page', count($pageIds)) . ' published.');
	}

	public function bulk_draft()
	{
		$pageIds = Input::get('pages');
		foreach ($pageIds as $pageId) {
			$page = Page::findOrFail($pageId);
			$page->is_online = 0;
			$page->is_current = 0;
			$page->save();
		}

		Cache::flush();
		return Redirect::back()->withSuccess(count($pageIds) . ' ' . str_plural('page', count($pageIds)) . ' unpublished.');
	}

	public function restore($id)
	{
		$warning = '';

		$page = Page::find($id);
		$page->is_deleted = 0;
		$page->save();
		$restored = 1;

		$parent = $page->parent()->first();
		if ( $parent ) {
			if ( $parent->is_deleted == 1 ) {
				$page->is_deleted = 1;
				$page->save();
				$restored = 0;
				$warning = "To restore children pages, parent pages must also be restored.";
			}
		}

		Cache::flush();
		return Redirect::back()->withSuccess($restored . ' ' . str_plural('page', $restored) . ' restored. ' . $warning);
	}

	public function bulk_restore()
	{
		$pageIds = Input::get('pages');
		$restored = 0;
		$warning = '';

		foreach ( $pageIds as $pageId ) {
			$page = Page::findOrFail( $pageId );
			$page->is_deleted = 0;
			$page->save();
			$restored++;
		}

		foreach ( $pageIds as $pageId ) {
			$page = Page::findOrFail( $pageId );
			$parent = $page->parent()->first();
			if ( $parent ) {
				if ( $parent->is_deleted == 1 ) {
					$page->is_deleted = 1;
					$page->save();
					$restored--;
					$warning = "To restore children pages, parent pages must also be restored.";
				}
			}
		}

		Cache::flush();
		return Redirect::back()->withSuccess($restored . ' ' . str_plural('page', $restored) . ' restored. ' . $warning);
	}

	public function versions($id)
	{
		$page = Page::findOrFail($id);
		$olderVersions = Page::with('user')->whereSlug($page->slug)->orderBy('version', 'asc')->get();

		$versions = new Versions($olderVersions);
		$versionsHtml = $versions->getVersionsHtml();

		return View::make('backend.pages.versions', [
					'page' => $page,
					'versionsHtml' => $versionsHtml,
					'user' => $this->user,
					'isAdmin' => $this->isAdmin,
					'logged_in_for' => $this->logged_in_for,
        			'activeParent' => $this->activeParent,
					'configs' => $this->configs,
					'active' => 'allpages'
				]);
	}

	public function select_version($id)
	{
		$currentVersion = Page::findOrFail($id);
		$selectedVersion = Page::findOrFail( Input::get('selectedVersion') );

		$currentVersion->is_latest = 0;
		$currentVersion->is_current = $selectedVersion->is_online ? 0 : $currentVersion->is_current;
		$currentVersion->save();

		$selectedVersion->is_latest = 1;
		$selectedVersion->is_current = $selectedVersion->is_online ? 1 : $selectedVersion->is_current;
		$selectedVersion->save();

		Cache::flush();
		return Redirect::to('dashboard/pages')->withSuccess($selectedVersion->title . ' page successfully reverted to version ' . $selectedVersion->version);
	}


// Sorting -------------------------------------------------------
	public function deleted_pages()
	{
		$pages = Page::getLatestVersions( 'deleted', $this->paginate );

		$backendPages = new BackendPages( $pages, $type = 'deleted' );
		$pagesHtml = $backendPages->getPagesHtml();

		$allNotDeletedNum = Page::getNotDeletedPagesNum( $this->cacheMinutes );
		$publishedNum  = Page::getPublishedPagesNum( $this->cacheMinutes );
		$draftsNum = Page::getDraftPagesNum( $this->cacheMinutes );
		$deletedNum = Page::getDeletedPagesNum( $this->cacheMinutes );

		$nums = compact("allNotDeletedNum", "publishedNum", "draftsNum", "deletedNum");

		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$configs = $this->configs;
		$logged_in_for = $this->logged_in_for;
		$pagesHtml = $pagesHtml;
		$nums = $nums;
		$activeParent = $this->activeParent;
		$active = 'allpages';
//		vlinks = $pages->links('backend.pagination.nifty');

		return View('nifty.backend.pages.deleted', compact(
			'user',
			'isAdmin',
			'configs',
			'logged_in_for',
			'pagesHtml',
			'nums',
			'activeParent',
			'active'
//			'links'
			));

	}


	public function published_pages()
	{
		$pages = Page::getLatestVersions( 'published', $this->paginate );

		$backendPages = new BackendPages( $pages, $type = 'published' );
		$pagesHtml = $backendPages->getPagesHtml();

		$allNotDeletedNum = Page::getNotDeletedPagesNum( $this->cacheMinutes );
		$publishedNum  = Page::getPublishedPagesNum( $this->cacheMinutes );
		$draftsNum = Page::getDraftPagesNum( $this->cacheMinutes );
		$deletedNum = Page::getDeletedPagesNum( $this->cacheMinutes );

		$nums = compact("allNotDeletedNum", "publishedNum", "draftsNum", "deletedNum");

		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$configs = $this->configs;
		$logged_in_for = $this->logged_in_for;
		$pagesHtml = $pagesHtml;
		$nums = $nums;
		$type = 'Published';
		$activeParent = $this->activeParent;
		$active = 'allpages';
//		$links = $pages->links('backend.pagination.nifty');

		return View('nifty.backend.pages.index', compact(
			'user',
			'isAdmin',
			'configs',
			'logged_in_for',
			'pagesHtml',
			'nums',
			'type',
			'activeParent',
			'active'
//			'links'
			));

	}


	public function draft_pages()
	{
		$pages = Page::getLatestVersions( 'drafts', $this->paginate );

		$backendPages = new BackendPages( $pages, $type = 'drafts' );
		$pagesHtml = $backendPages->getPagesHtml();

		$allNotDeletedNum = Page::getNotDeletedPagesNum( $this->cacheMinutes );
		$publishedNum  = Page::getPublishedPagesNum( $this->cacheMinutes );
		$draftsNum = Page::getDraftPagesNum( $this->cacheMinutes );
		$deletedNum = Page::getDeletedPagesNum( $this->cacheMinutes );

		$nums = compact("allNotDeletedNum", "publishedNum", "draftsNum", "deletedNum");

		$user = $this->user;
		$isAdmin = $this->isAdmin;
		$configs = $this->configs;
		$logged_in_for = $this->logged_in_for;
		$pagesHtml = $pagesHtml;
		$nums = $nums;
		$type = 'Drafts';
		$activeParent = $this->activeParent;
		$active = 'allpages';
//		$links = $pages->links('backend.pagination.nifty');

		return View('nifty.backend.pages.index', compact(
			'user',
			'isAdmin',
			'configs',
			'logged_in_for',
			'pagesHtml',
			'nums',
			'type',
			'activeParent',
			'active'
//			'links'
			));

	}


}
