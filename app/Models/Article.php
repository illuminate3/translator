<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Vinkla\Translator\Translatable;
use Vinkla\Translator\Contracts\Translatable as TranslatableContract;

class Article extends Model implements TranslatableContract {

	use Translatable;

    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'image'];

    /**
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @var string
     */
    protected $translator = 'App\Models\ArticleTranslation';

	/**
	 * @var array
	 */
	protected $translatedAttributes = ['title', 'content'];

	/**
	 * @var array
	 */
	protected $appends = ['title', 'content'];

	/**
	 * Fetch the translated title attribute.
	 *
	 * @return string
	 */
	public function getTitleAttribute()
	{
		return $this->title;
	}

	/**
	 * Fetch the translated content attribute.
	 *
	 * @return string
	 */
	public function getContentAttribute()
	{
		return $this->content;
	}

}
