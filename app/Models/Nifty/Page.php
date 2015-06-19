<?php
use Baum\Node;

/**
* MODEL
*/
class Page extends Node {

    protected $table = 'pages';

	public static $rules = [
								'title' => 'required|max:255',
								'summary' => 'required|max:512',
								'content' => 'required',
								'order' => 'integer',
                                'link' => 'URL'
							];

	public function user()
	{
		return $this->belongsTo('User');
	}

    public static function getLatestVersions($type, $paginate)
    {
        $pages = '';
        switch ($type) {
            case "allNotDeleted":
                if ( isset($_GET['author']) ) {
                    $pages = static::whereHas('user', function($query) { $query->whereId($_GET['author']); })
                                    ->whereIsLatest(1)
                                    ->whereIsDeleted(0)
                                    ->orderBy('order')
                                    ->paginate( (int) $paginate );
                }
                else {
                    $pages = static::with('user')
                                    ->whereIsLatest(1)
                                    ->whereIsDeleted(0)
                                    ->orderBy('order')
                                    ->paginate( (int) $paginate );
                }
                break;
            case "published":
                $pages = static::with('user')
                                ->whereIsLatest(1)
                                ->whereIsDeleted(0)
                                ->whereIsOnline(1)
                                ->orderBy('order')
                                ->paginate( (int) $paginate );
                break;
            case "drafts":
                $pages = static::with('user')
                                ->whereIsLatest(1)
                                ->whereIsDeleted(0)
                                ->whereIsOnline(0)
                                ->orderBy('order')
                                ->paginate( (int) $paginate );
                break;
            case "deleted":
                $pages = static::with('user')
                                ->whereIsLatest(1)
                                ->whereIsDeleted(1)
                                ->orderBy('order')
                                ->paginate( (int) $paginate );
                break;
        }

        return $pages;
    }

    public static function countPages($type)
    {
        $num = 0;

        switch ($type) {
            case "allNotDeleted":
                $num = static::whereIsLatest(1)->whereIsDeleted(0)->count();
                break;
            case "published":
                $num = static::whereIsLatest(1)->whereIsDeleted(0)->whereIsOnline(1)->count();
                break;
            case "drafts":
                $num = static::whereIsLatest(1)->whereIsDeleted(0)->whereIsOnline(0)->count();
                break;
            case "deleted":
                $num = static::whereIsLatest(1)->whereIsDeleted(1)->count();
                break;
        }

        return $num;
    }

    public static function getNotDeletedPagesNum( $minutes )
    {
        $allNotDeletedNum = Cache::remember('allNotDeletedNumPages', $minutes, function()
        {
            return static::countPages('allNotDeleted');
        }); 

        return $allNotDeletedNum;
    }

    public static function getPublishedPagesNum( $minutes )
    {
        $publishedNum = Cache::remember('publishedNumPages', $minutes, function()
        {
            return static::countPages('published');
        });

        return $publishedNum;
    }

    public static function getDraftPagesNum( $minutes )
    {
        $draftsNum = Cache::remember('draftsNumPages', $minutes, function()
        {
            return static::countPages('drafts');
        });

        return $draftsNum;
    }

    public static function getDeletedPagesNum( $minutes )
    {
        $deletedNum = Cache::remember('deletedNumPages', $minutes, function()
        {
            return static::countPages('deleted');
        });

        return $deletedNum;
    }    

    // public static function getSinglePage($slug) //Check
    // {
    //     return static::with('user')
    //                     ->whereIsDeleted(0)
    //                     ->whereIsOnline(1)
    //                     ->whereSlug($slug)
    //                     ->first();
    // }

    public static function getparentOptions($exceptId)
    {
        return $exceptId ? ['0' => '(no parent)'] + static::whereIsLatest(1)
                                                            ->whereIsDeleted(0)
                                                            ->where('slug', '<>', 'home-page')
                                                            ->where('slug', '<>', 'search')
                                                            ->whereNotIn('id', [$exceptId])
                                                            ->lists('title', 'id')
                        : ['0' => '(no parent)'] + static::whereIsLatest(1)
                                                            ->whereIsDeleted(0)
                                                            ->where('slug', '<>', 'home-page')
                                                            ->where('slug', '<>', 'search')
                                                            ->lists('title', 'id');
    }

    public static function getAllVersions($page)
    {
        return static::whereSlug($page->slug)->get();
    }



##################FRONTEND PAGES METHODS########################################
    
    public static function getPage( $slug )
    {
       $page =  static::whereIsCurrent(1)
                       ->whereIsOnline(1)
                       ->whereIsDeleted(0)
                       ->whereSlug($slug)
                       ->first();

        return $page;
    }

    public static function getPreviewPage( $id )
    {
        $page =  static::find($id)->first();

        return $page;
    }

    public static function getRoots()
    {  
        // $roots = Cache::rememberForever('roots', function()
        // {
            return static::whereIsCurrent(1)
                            ->whereIsOnline(1)
                            ->whereIsDeleted(0)
                            ->whereParentId(NULL)
                            ->where('slug', '<>', 'home-page')
                            ->where('slug', '<>', 'search')
                            ->where('slug', '<>', 'terms-conditions')
                            ->orderBy('order')
                            ->get();
        // });

        // return $roots;
    }

    public static function getChildren($root)
    {   //Cache::flush();
        //immediateDescendants()
        // $frontChildren = Cache::rememberForever('frontChildren_' . $root->id, function() use ($root)
        // {
            return static::where('parent_id', $root->id)
                        ->whereIsCurrent(1)
                        ->whereIsOnline(1)
                        ->whereIsDeleted(0)
                        ->orderBy('order')
                        ->get();
        // });

        // return $frontChildren;
    }

    public static function doSearch($term)
    {
        return static::whereIsCurrent(1)
                        ->whereIsOnline(1)
                        ->whereIsDeleted(0)
                        ->where( function($query) use($term) {
                            $query->where('title', 'like', "%$term%")
                                ->orWhere('summary', 'like', "%$term%")
                                ->orWhere('content', 'like', "%$term%");
                            })
                        ->get();        
    }

}
