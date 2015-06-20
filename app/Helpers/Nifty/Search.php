<?php
namespace Jamesy;
use URL;
use Str;
use Page;
use Post;

class Search
{

	public static function getSearchResults($term)
	{
		$pages = Page::doSearch($term);
		$posts = Post::doSearch($term);

		$results = [];

		foreach ($pages as $page) {
			$results[] = ['title' => $page->title, 'summary' => $page->summary, 'url' => static::makePageUrl($page)];
		}

		foreach ($posts as $post) {
			$results[] = ['title' => $post->title, 'summary' => $post->summary, 'url' => static::makePostUrl($post)];
		}

		return $results;		
	}


	public static function makePageUrl($page)
	{
		$url = '#';

		switch ( $page->getLevel() ) {
			case 0:
				$url = $page->slug;
				break;
			case 1:
				$url = $page->getRoot()->slug . '/' . $page->slug;
				break;
			case 2:
				$root = $page->getRoot();
				$granRoot = $root->getRoot();
				$url =  $granRoot->slug . '/' . $root->slug . '/' . $page->slug;
				break;								
		}

		return URL::to($url);
	}


	public static function makePostUrl($post)
	{
		$allCategories = ['latest news'];
		$postCategories = $post->categories;
		$url = '#';
		$category = '';				

		foreach ($postCategories as $postCategory) {
			if ( in_array( Str::lower($postCategory->name), $allCategories ) ) {
				$category = $postCategory->name;
				break;
			}
		}

		switch ($category) {
			case 'Latest News':
				$url = 'latest-news/';
				break;			
			default:
				$url = 'blog/';
				break;
		}	

		return $post->link ? $post->link : URL::to($url . $post->slug);	
	}

}