<?php
namespace App\Http\Controllers;

use App\Models\Nifty\Page;
//use App\Models\Repositories\PageRepository;

use Illuminate\Http\Request;
use App\Http\Requests\DeleteRequest;
// use App\Http\Requests\PageCreateRequest;
// use App\Http\Requests\PageUpdateRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;
use Config;
//use Datatables;
use Flash;
use Hashids\Hashids;
use Route;
use Theme;


class FrontendController extends Controller {

	public function __construct()
	{
		$this->page = Route::current()->parameter('page');
		$slugs = explode('/', $this->page);
		$lastSlug = Route::current()->getName() == 'search' ? 'search' : $slugs[count($slugs)-1];
		$this->currentPage = Page::getPage( $slug = $lastSlug );
		$this->roots = Page::getRoots();
		$this->postsOrderBy = ['id', 'desc'];
		$this->postsOrderByOrder = ['order', 'asc'];
		$this->hashIds = new Hashids( Config::get('app.key'), 8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' );
		$this->postItemsNum = 10;
		$this->postItemsPerPage = 2;
		// $this->latestNewsPosts = Post::getLatestNewsPosts($this->postItemsNum, $this->postsOrderBy);
		$this->contact = ["Demo NiftyCMS", "demo@niftycms.com"];
	}

	public function index()
	{
		if ( $homePage = Page::getPage( $slug = 'home-page' ) ) {
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $homePage );
			// $posts = Post::getFrontendPosts($category = 'Home Featured', $this->postsOrderBy);
			return View::make('frontends.index', ['page' => $homePage, /*'posts' => $posts,*/ 'mainMenu' => $mainMenu]);
		}
		else
			App::abort(404);
	}

	public function contact_us()
	{
		if ( $contact_us = Page::getPage( $slug = 'contact-us' ) ) {
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $contact_us );
			$root = $contact_us->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu($root, $contact_us);
			return View::make('frontends.contact-us', ['page' => $contact_us, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function do_contact_us()
	{
		$inputs = [];
		foreach(Input::all() as $key=>$input)
		{
			$inputs[$key] = \Jamesy\Sanitiser::trimInput($input);
		}

		$rules = [
					'name' => 'required|max:255',
					'email' => 'required|email',
					'subject' => 'required',
					'message' => 'required'
				];

		$validation = \Jamesy\MyValidations::validate($inputs, $rules);

		if($validation != NULL) {
			return Redirect::back()->withErrors($validation)->withInput();
		}

		else {
    		$data = [ 'name' => $inputs['name'], 'emailbody' => $inputs['message'] ];
    		$to_email = $this->contact[1];
    		$to_name = $this->contact[0];

			$issent =
			Mail::send('emails.contact-us', $data, function($message) use ($inputs, $to_email, $to_name)
			{
			    $message->from($inputs['email'], $inputs['name'])->to($to_email, $to_name)->subject('Website Contact Us: ' . $inputs['subject']);
			});

			if ($issent) {
				$feedback = ['success', 'Message successfully sent. We will be in touch soon'];
			}

			else {
				$feedback = ['failure', 'Your email was not sent. Kindly try again.'];
			}

			return Redirect::to('contact-us')->with($feedback[0], $feedback[1]);
		}
	}

	public function get_page()
	{
		if ( $this->currentPage ) {
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $this->currentPage );
			$root = $this->currentPage->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu($root, $this->currentPage );

			return View::make('frontends.page', ['page' => $this->currentPage, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function previewPage($hashedId)
	{
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$previewPage = Page::getPreviewPage( $id );
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $previewPage );
			$root = $previewPage->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu( $root, $previewPage );

			return View::make('frontends.page', ['page' => $previewPage, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_blog()
	{
		if ( $blog = Page::getPage( $slug = 'blog' ) ) {
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu($root, $blog);

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.blog', ['page' => $blog, 'posts' => $posts, 'links' => $posts->links('backend.pagination.nifty'), 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function get_post()
	{
		$slugs = explode( '/', Route::current()->parameter('any') );
		$lastSlug = $slugs[count($slugs)-1];

		if ( $blog = Page::getPage( $slug = 'blog' ) ) {
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $blog );
			$root = $blog->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu($root, $blog);

			$post = Post::getFrontendPost( $lastSlug );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.post', ['page' => $post, 'posts' => $posts, 'active' => '', 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function previewPost($hashedId)
	{
		$id = $this->hashIds->decrypt($hashedId)[0];

		if ( $id ) {
			$blogPage = Page::getPage( $lug = 'blog' );
			$blogPost = Post::find($id);
			$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $blogPage );
			$root = $blogPage->getRoot();
			$secMenu = \Jamesy\NiftyMenus::getSecMenu( $root, $blogPage );

			$posts = Post::getFrontendPosts( $this->postsOrderBy, $this->postItemsNum, $this->postItemsPerPage );

			return View::make('frontends.post', ['page' => $blogPost, 'posts' => $posts, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
		}
		else
			App::abort(404);
	}

	public function do_search()
	{
		$term = \Jamesy\Sanitiser::trimInput( Input::get('term') );
		$results = \Jamesy\Search::getSearchResults($term);

		$searchPage = Page::getPage( $slug = 'search' );
		$mainMenu = \Jamesy\NiftyMenus::getMainMenu( $searchPage );
		$secMenu = '';

		return View::make('frontends.search', ['page' => $searchPage, 'term' => $term, 'results' => $results, 'mainMenu' => $mainMenu, 'secMenu' => $secMenu]);
	}

}
