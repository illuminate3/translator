<?php
namespace App\Helpers\Nifty;
//namespace Jamesy;

use App\Models\Nifty\Page;
use App\Models\Content;
// use App\Models\Repositories\ContentRepository;
// use App\Models\Locale;

use Session;
use URL;


class NiftyMenus
{


// 	public function __construct(
// 			Content $content,
// 			ContentRepository $content_repo
// 		)
// 	{
// 		$this->content = $content;
// 		$this->content_repo = $content_repo;
// 	}


	public static function getMainMenu($currentPage)
	{
//dd($currentPage);
// 		$lang = Session::get('locale');
//  		$locales = Locale::all();
		$locale_id = 1;

//dd('getMainMenu');


		$roots = Page::getRoots();
//		$roots = ContentRepository::getRoots($locale_id);
//		$roots = ContentRepository::getStaticRoots($locale_id);
//		$roots = Content::getStaticRoots($locale_id);
//		$roots = Content::getRootsStatic();
//dd($roots);


//		$currentRoot = $currentPage->getRoot();
		$currentRoot = $currentPage->getRoot();
//		$currentRoot = Content::getRoots();
		$html = '';

		foreach ( $roots as $root ) {
			if ( $root->getLevel() == 0 ) {
				if ( $root->id == $currentRoot->id )
					$html .= "<li class='active niftyNavLi'><a href='" . URL::to($root->slug) . "'>" . $root->title . "<span class='float-right'><i class='glyphicon glyphicon-chevron-down'></i></span></a>";
				else
					$html .= "<li class='niftyNavLi'><a href='" . URL::to($root->slug) . "'>" . $root->title . "<span class='float-right'><i class='glyphicon glyphicon-chevron-down'></i></span></a>";

				$html .= "<ul class='secondary-nav'>";
				$html .= static::getSecMenu($root, $currentPage);
				$html .= "</ul></li>";
			}
		}

		return $html;
	}


	public static function getSecMenu($root, $currentPage)
	{
//dd('getSecMenu');
		$children = Page::getChildren($root);

		if ( $root->id == $currentPage->id )
			$html = "<a class='list-group-item active' href='" . URL::to($root->slug) . "' >$root->title</a>";

		else
			$html = "<a class='list-group-item' href='" . URL::to($root->slug) . "' >$root->title</a>";

		foreach ( $children as $child ) {
			if ( $child->slug == $currentPage->slug )
				$html .= "<a class='list-group-item active' href='" . URL::to($root->slug . '/' . $child->slug) . "' >" . $child->title . "</a>";
			else
				$html .= "<a class='list-group-item' href='" . URL::to($root->slug . '/' . $child->slug) . "' >" . $child->title . "</a>";
		}

		return $html;

	}


}
