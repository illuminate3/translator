<?php
namespace Jamesy;

use \Carbon\Carbon;

class FormatDate
{
	public static function getStandardFormat( $eloquentDate )
	{
		return "<abbr title='" . $eloquentDate->format('D jS \\of M, Y H:i A') . "'>" . $eloquentDate->format('jS M, Y') . "</abbr>";
	}
}