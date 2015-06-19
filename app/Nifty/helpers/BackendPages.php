<?php 
namespace Jamesy;
use Carbon\Carbon;
use Str;
use Sentry;
use URL;
use Config;
use Hashids\Hashids;

class BackendPages
{


	public function __construct($pages, $type)
	{
		$this->pages = (object) $pages;	
		$this->pagesArray = $this->getConvertedToArray($pages);
		$this->type = $type;
		$this->userGroup = Str::lower(Sentry::getUser()->getGroups()[0]);
		$this->hashids = new Hashids(Config::get('app.key'), 8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
	}

	public function getConvertedToArray($pages)
	{
		$pagesArray = [];
		foreach ($pages as $page) {
			$pagesArray[] = $page;
		}

		return $pagesArray;
	}

	public function getParents()
	{
		$pages = $this->pagesArray;
		$parents = [];

		foreach ( $pages as $page ) {
			if ( $page->parent_id == NULL )
				$parents[] = $page;
		}

		return $parents;
	}

	public function getHighestPages()
	{
		$pages = $this->pagesArray;
		$depth = 999;  

		$highestLevel = [];

		foreach ($pages as $page) {
			if ( (int) $page->depth < $depth ) {
				$depth = $page->depth;
			}
		}

		foreach ($pages as $page) {
			if ( $page->depth == $depth ) 
				$highestLevel[] = $page;
		}

		return $highestLevel;
	}

	public function getDirectChildren($parent)
	{
		$pages = $this->pagesArray;
		$children = [];

		foreach ( $pages as $child ) {
			if ( $child->parent_id == $parent->id )
				$children[] = $child;
		}

		return $children;
	}

	public function getPagesHtml()
	{
		$parents = $this->getHighestPages();
		$this->pagesArray = array_diff($this->pagesArray, $parents);
		$html = '';
		$hashids = $this->hashids;

		if ( $this->type == 'deleted' ) {
			foreach ($parents as $parent) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $parent->id . "'></td>";
				$html .= "<td>$parent->title";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/restore') . "'>Restore</a> | ";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $parent->user->id) . "'>" . $parent->user->first_name .' '. $parent->user->last_name . "</a></td>";
				$html .= "<td>$parent->version</td>";
				$html .= "<td><abbr title='" . $parent->created_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $parent->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $parent->isLeaf() ) {
					$arrow = "&raquo;";
					$html .= $this->getChildrenHTML($parent,$arrow);
				}
			}
		}

		else {
			foreach ($parents as $parent) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $parent->id . "'></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $parent->id . '/edit') . "'><strong>$parent->title</strong></a>";
				if ( $this->type == 'all' ) $html .= ! $parent->is_online ? "<strong> - Draft </strong>" : "";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/edit') . "'>Edit</a> | ";
				$html .= $parent->is_online ? "<a href='" . URL::to('scaffolding/'.$parent->slug) . "' target='_blank' >View</a>" : "<a href='" . URL::to('dashboard/pages/' . $hashids->encrypt($parent->id) . '/preview') . "' target='_blank'>Preview</a>";
				$html .= " | <a href='" . URL::to('dashboard/pages/' . $parent->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $parent->user->id) . "'>" . $parent->user->first_name .' '. $parent->user->last_name . "</a></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $parent->id) . "/versions' class='btn btn-default btn-circle btn-grad btn-sm'>$parent->version</a></td>";
				$html .= "<td><abbr title='" . $parent->created_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $parent->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $parent->isLeaf() ) {
					$arrow = "&raquo;";
					$html .= $this->getChildrenHTML($parent,$arrow);
				}
			}

		}

		if ( count( $this->pagesArray ) ) {
			$html .= $this->getPagesHtml();
		}		

		return $html;
	}


	public function getTopLevelHtml($pagesArray, $parents)
	{
		$html = '';
		$hashids = $this->hashids;

		if ( $this->type == 'deleted' ) {
			foreach ($parents as $parent) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $parent->id . "'></td>";
				$html .= "<td><strong>$parent->title</strong>";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/restore') . "'>Restore</a> | ";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $parent->user->id) . "'>" . $parent->user->first_name .' '. $parent->user->last_name . "</a></td>";
				$html .= "<td>$parent->version</td>";
				$html .= "<td><abbr title='" . $parent->created_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $parent->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $parent->isLeaf() ) {
					$arrow = "&raquo;";
					$html .= $this->getChildrenHTML($parent,$arrow);
				}
			}
		}

		else {
			foreach ($parents as $parent) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $parent->id . "'></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $parent->id . '/edit') . "'><strong>$parent->title</strong></a>";
				if ( $this->type == 'all' ) $html .= ! $parent->is_online ? "<strong> - Draft </strong>" : "";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $parent->id . '/edit') . "'>Edit</a> | ";
				$html .= $parent->is_online ? "<a href='" . URL::to('scaffolding/'.$parent->slug) . "' target='_blank'>View</a>" : "<a href='" . URL::to('dashboard/pages/' . $hashids->encrypt($parent->id) . '/preview') . "' target='_blank'>Preview</a>";
				$html .= " | <a href='" . URL::to('dashboard/pages/' . $parent->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $parent->user->id) . "'>" . $parent->user->first_name .' '. $parent->user->last_name . "</a></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $parent->id) . "/versions' class='btn btn-default btn-circle btn-grad btn-sm'>$parent->version</a></td>";
				$html .= "<td><abbr title='" . $parent->created_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $parent->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $parent->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $parent->isLeaf() ) {
					$arrow = "&raquo;";
					$html .= $this->getChildrenHTML($parent,$arrow);
				}
			}

		}

		return $html;
	}


	public function getChildrenHTML($parent,$arrow)
	{
		$cacheArrow = $arrow;
		$children = $this->getDirectChildren($parent);
		$this->pagesArray = array_diff($this->pagesArray, $children);
		$html = ''; 
		$hashids = $this->hashids;

		if ( $this->type == 'deleted' ) {
			foreach ($children as $child) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $child->id . "'></td>";
				$html .= "<td>$arrow $child->title</a>";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $child->id . '/restore') . "'>Restore</a> | ";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $child->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $child->user->id) . "'>" . $child->user->first_name .' '. $child->user->last_name . "</a></td>";
				$html .= "<td>$child->version</td>";
				$html .= "<td><abbr title='" . $child->created_at->format('D jS \\of M, Y H:i A') . "'>" . $child->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $child->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $child->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $child->isLeaf() ) {
					$arrow .= "&raquo;";
					$html .= $this->getChildrenHTML($child,$arrow);

				}

				$arrow = $cacheArrow;
			}
		}

		else {
			foreach ($children as $child) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $child->id . "'></td>";
				$html .= "<td>$arrow <a href='" . URL::to('dashboard/pages/' . $child->id . '/edit') . "'>$child->title</a>";
				if ( $this->type == 'all' ) $html .= ! $child->is_online ? "<strong> - Draft </strong>" : "";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/pages/' . $child->id . '/edit') . "'>Edit</a> | ";
				$html .= $child->is_online ? "<a href='" . URL::to('scaffolding/'.$child->slug) . "' target='_blank'>View</a>" : "<a href='" . URL::to('dashboard/pages/' . $hashids->encrypt($child->id) . '/preview') . "' target='_blank'>Preview</a>";
				$html .= " | <a href='" . URL::to('dashboard/pages/' . $child->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages?author=' . $child->user->id) . "'>" . $child->user->first_name .' '. $child->user->last_name . "</a></td>";
				$html .= "<td><a href='" . URL::to('dashboard/pages/' . $child->id) . "/versions' class='btn btn-default btn-circle btn-grad btn-sm'>$child->version</a></td>";
				$html .= "<td><abbr title='" . $child->created_at->format('D jS \\of M, Y H:i A') . "'>" . $child->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $child->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $child->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";

				if( ! $child->isLeaf() ) {
					$arrow .= "&raquo;";
					$html .= $this->getChildrenHTML($child,$arrow);

				}

				$arrow = $cacheArrow;
			}
		}

		return $html;
	}



}