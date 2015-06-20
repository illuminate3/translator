<?php
namespace Jamesy;
use Validator;

	class MyValidations
	{
		
		public static function validate($inputs, $rules)
		{
			$validation = Validator::make($inputs, $rules);

			if($validation->fails())
			{
				return $validation->messages();
			}	
		
		}

		public static function makeSlug($existingSlugs, $proposedSlug)
		{
			$exists = false;

			foreach ($existingSlugs as $existingSlug) {
				if ( $proposedSlug == $existingSlug ) {
					$exists = $existingSlug;
					break;
				}
			}

			if( $exists ) {
				$parts = explode("-", $exists);
				$length = count($parts);
				$lastPart = $parts[$length-1];
				$newSlug = "";

				if( (int) $lastPart > 0 ) {
					$allOtherParts = $parts; 
					unset($allOtherParts[$length-1]); 
					$allOtherParts[] = (int) $lastPart + 1;

					foreach ($allOtherParts as $key=>$part) {
						if( $key != $length )
							$newSlug .= $part . "-";
						else
							$newSlug .= $part;
					}
				}

				else {
					$newSlug = $exists . "-1";
				}

				return static::makeSlug($existingSlugs, $newSlug);
			}

			else {
				return $proposedSlug;
			}
		}

		public static function assignVersion($existingVersions, $proposedVersion)
		{
			$exists = false;
			
			foreach ($existingVersions as $existingVersion) {
				if( $proposedVersion == $existingVersion ) {
					$exists = $existingVersion;	
					break;
				}

			}	

			if ( $exists ) {
				return static::assignVersion($existingVersions, $proposedVersion + 1);
			}

			else {
				return $proposedVersion;
			}
		}

	}

	class Sanitiser
	{
		public static function trimInput($input)
		{
			return trim($input);
		}
	}