<?php
namespace Jamesy;

use URL;
use Page;

class NiftyMenus 
{
	public static function getMainMenu($currentPage)
	{
		$roots = Page::getRoots();
		$currentRoot = $currentPage->getRoot();
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