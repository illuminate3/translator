<?php

namespace Jamesy;

use Page;

class NodeDeletion 
{
	public function __construct($node,$descendants)
	{
		$this->node = $node;
		$this->childrenNum = count($this->node->getImmediateDescendants());
		$this->descendants = $descendants;
	}

	public function softDeleteAllDescendants()
	{
		$descendants = $this->descendants;

		foreach ($descendants as $descendant) {
			$descendant->is_deleted = 1;
			$descendant->save();
		}

		return count($descendants);
	}

	public function destroyAllDescendants()
	{
		$descendants = $this->descendants;
		$num = count($descendants);

		if ( $num ) {
			foreach ($descendants as $descendant) {
				$this->destroyAllVersions($descendant);
			}			
		}

		return $num;
	}

	public function destroyAllVersions($page)
	{
		$allVersions = Page::getAllVersions($page);

		foreach ($allVersions as $aVersion) {
			$aVersion->delete();
		}
	}

	public function makeChildrenRoot()
	{
		$node = $this->node;
		$childrenNum = $this->childrenNum;
		
		for ( $i=0; $i<$childrenNum; $i++ ) {
			$node->getImmediateDescendants()[0]->makeRoot();
		}

	}

	public function assignParent($newParentId)
	{
		$newParent = Page::findOrFail( (int) $newParentId );
		$node = $this->node;
		$childrenNum = $this->childrenNum;

		if ( $newParent ) {
			for ( $i=0; $i < $childrenNum; $i++ ) { 
				$node->getImmediateDescendants()[0]->makeChildOf($newParent);
			}
		}
	}
}