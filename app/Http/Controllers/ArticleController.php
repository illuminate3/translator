<?php namespace App\Http\Controllers;

use App\Article;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;

class ArticleController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * List all articles.
	 *
	 * @return Response
	 */
	public function index()
	{
		return Article::all();
	}

	/**
	 * List a specific article.
	 *
	 * @return Response
	 */
	public function show($id)
	{
		return Article::find($id);
	}

	/**
	 * Store a new article.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Set the locale you want to store.
		App::setLocale('en');

		// Create a new article.
		Article::create(Input::all());

		return redirect('articles');
	}

	/**
	 * Update an article.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		// Set the locale you want to update.
		App::setLocale('sv');

		// Fetch the article.
		$article = Article::findOrFail($id);

		// Update the article.
		$article->update(Input::all());

		return $article;
	}

	/**
	 * Delete an article. This will automagically delete
	 * the translations for the article as well.
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		// Fetch the article.
		$article = Article::findOrFail($id);

		// Delete the article.
		$article->delete();

		// Redirect back to articles page.
		return redirect('articles');
	}

}
