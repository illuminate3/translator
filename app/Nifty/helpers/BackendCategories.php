<?php
namespace Jamesy;

use URL;
use Carbon\Carbon;

class BackendCategories
{
	public static function getCategoriesHtml($categories)
	{
		$html = '';

		foreach ($categories as $category) {
			$html .= "<tr class='hover-row'>";
			$html .= "<td><input type='checkbox' class='acheckbox' value='" . $category->id . "'></td>";
			$html .= "<td><a href='" . URL::to('dashboard/blog/categories/' . $category->id . '/edit') . "'>$category->name</a>";
			$html .= "<div class='visibility more-options'>";
			$html .= "<a href='" . URL::to('dashboard/blog/categories/' . $category->id . '/edit') . "'>Edit</a> | ";
			$html .= "<a href='" . URL::to('dashboard/blog/categories/' . $category->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";			
			if ( count($category->posts) )
				$html .= "<td><a href='" . URL::to('dashboard/blog?category=' . $category->id) . "' class='btn btn-default btn-circle btn-grad btn-sm'>" . count($category->posts) . "</a></td>";
			else
				$html .= "<td><a href='" . URL::to('dashboard/blog?category=' . $category->id) . "' class='btn btn-default btn-circle btn-grad btn-sm disabled'>" . count($category->posts) . "</a></td>";
			$html .= "<td><abbr title='" . $category->created_at->format('D jS \\of M, Y H:i A') . "'>" . $category->created_at->format('jS M, Y') . "</abbr></td>";
			$html .= "<td><abbr title='" . $category->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $category->updated_at->diffForHumans() . "</abbr></td>";
			$html .= "</tr>";
		}

		return $html;
	}
}