<?php
namespace Jamesy;

use Config;
use URL;
use Hashids\Hashids;
use Carbon\Carbon;

class Versions
{
	public function __construct($olderVersions)
	{
		$this->olderVersions = $olderVersions;
		$this->hashids = new Hashids(Config::get('app.key'), 8, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
	}

	public function getVersionsHtml()
	{
		$olderVersions = $this->olderVersions;
		$hashids = $this->hashids;
		$html = '';

		foreach ( $olderVersions as $key => $page ) {
			$num = (int) $key+1;
			$html .= $page->is_latest ? "<tr class='active'>" : "<tr class='hover-row'>";
			$radio = $page->is_latest ? "<input type='radio' name='selectedVersion' class='selectedVersion' value='" . $page->id . "' rel='" . $page->version  . "' checked />" : 
										"<input type='radio' name='selectedVersion' class='selectedVersion' value='" . $page->id . "' rel='" . $page->version  . "' />";

			$html .= "<td>$num</td>
					  <td><strong>$page->title</strong> 
							<div class='visibility more-options'> 
								<a href='" . URL::to('dashboard/pages/' . $hashids->encrypt( $page->id ) . '/preview') . "' target='_blank'>Preview</a> | 
								<a href='" . URL::to('dashboard/pages/' . $page->id . '/destroy') . "' class='text-danger'>Delete Permanently</a>
							</div>
					  </td>
					  <td>" . $page->user->first_name .' '. $page->user->last_name . "</td>
					  <td>$page->version</td>
					  <td><abbr title='" . $page->created_at->format('D jS \\of M, Y H:i A') . "'>" . $page->created_at->format('jS M, Y') . "</abbr></td>
					  <td><abbr title='" . $page->updated_at->format('D jS \\of M, Y H:i A') . "'>" . $page->updated_at->diffForHumans() . "</abbr></td>
					  <td>$radio</td>";
			$html .= "</tr>";
		}

		return $html;
	} 

}