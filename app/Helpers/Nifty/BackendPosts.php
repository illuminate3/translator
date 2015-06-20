<?php
namespace Jamesy;

use Carbon\Carbon; 
use URL;
use Hashids\Hashids;
use Config;
use Str;

class BackendPosts
{
	public static function getPostsHtml($posts, $type)
	{
		$html = '';

		if ( $type == 'deleted' ) {
			foreach ($posts as $post) {
				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $post->id . "'></td>";
				$html .= "<td>$post->title";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/blog/posts/' . $post->id . '/restore') . "'>Restore</a> | ";
				$html .= "<a href='" . URL::to('dashboard/blog/posts/' . $post->id . '/destroy') . "' class='text-danger'>Delete Permanently</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/blog?author=' . $post->user->id) . "'>" . $post->user->first_name . ' ' . $post->user->last_name . "</a></td><td>";
					$categoriesNum = count($post->categories);
					if ( $categoriesNum == 0 ) {
						$html .= 'Uncategorised';
					}
					else {
						foreach ($post->categories as $key => $category) {
							$html .= "<a href='" . URL::to('dashboard/blog?category=' . $category->id) . "'>$category->name</a>";
							$html .= $key != $categoriesNum - 1 ? ", " : "";
						}
					}
				$html .= "</td>";
				$html .= "<td><abbr title='" . $post->created_at->format('D jS \\of M, Y H:i A') . "'>" . $post->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $post->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $post->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";
			}
		}

		else {
			$hashids = new Hashids(Config::get('app.key'), 8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

			foreach ($posts as $post) {

				$viewLink = $post->link ? $post->link : URL::to('blog/' . $post->slug);
				$previewLink = $post->link ? $post->link : URL::to('dashboard/blog/posts/' . $hashids->encrypt($post->id) . '/preview');

				$html .= "<tr class='hover-row'>";
				$html .= "<td><input type='checkbox' class='acheckbox' value='" . $post->id . "'></td>";
				$html .= "<td><a href='" . URL::to('dashboard/blog/posts/' . $post->id . '/edit') . "'>$post->title</a>";
				if ( $type == 'all' ) 
					$html .= !$post->is_online ? "<strong> - Draft </strong>" : "";
				$html .= "<div class='visibility more-options'>";
				$html .= "<a href='" . URL::to('dashboard/blog/posts/' . $post->id . '/edit') . "'>Edit</a> | ";
				$html .= $post->is_online ? "<a href='" . $viewLink . "' target='_blank'>View</a>" : "<a href='" . $previewLink . "' target='_blank'>Preview</a>";
				$html .= " | <a href='" . URL::to('dashboard/blog/posts/' . $post->id . '/delete') . "' class='text-danger'>Trash</a></div></td>";
				$html .= "<td><a href='" . URL::to('dashboard/blog?author=' . $post->user->id) . "'>" . $post->user->first_name . ' ' . $post->user->last_name . "</a></td><td>";
					$categoriesNum = count($post->categories);
					if ( $categoriesNum == 0 ) {
						$html .= 'Uncategorised';
					}
					else {
						foreach ($post->categories as $key => $category) {
							$html .= "<a href='" . URL::to('dashboard/blog?category=' . $category->id) . "'>$category->name</a>";
							$html .= $key != $categoriesNum - 1 ? ", " : "";
						}
					}
				$html .= "</td>";
				$html .= "<td><abbr title='" . $post->created_at->format('D jS \\of M, Y H:i A') . "'>" . $post->created_at->format('jS M, Y') . "</abbr></td>";
				$html .= "<td><abbr title='" . $post->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $post->updated_at->diffForHumans() . "</abbr></td>";
				$html .= "</tr>";
			}
		}

		return $html;
	}
}