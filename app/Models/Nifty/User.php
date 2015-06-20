<?php
namespace App\Models\Nifty;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

use App\Models\Nifty\Page;

class User extends Model implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	public static $rules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'email' => 'required|email|unique:users'
						   ];

	public static $editRules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128'
						   ];

	public static $passwordRules = [ 'new_password' => 'required|min:6|confirmed' ];

	public static $newUserRules = [
								'first_name' => 'required|max:128',
								'last_name' => 'required|max:128',
								'email' => 'required|email|unique:users',
								'password' => 'required|min:6|confirmed',
								'role' => 'required|integer'
						   ];

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function pages()
	{
		return $this->hasMany('Page');
	}

	public function posts()
	{
		return $this->hasMany('Post');
	}

	public static function isAdmin( $user )
	{
		$isAdmin = false;

		$admin = Sentry::findGroupByName('Administrator');
		if ( $user->inGroup($admin) )
			$isAdmin = true;

		return $isAdmin;
	}

	public static function isPublisher( $user )
	{
		$isPublisher = false;

		$publisher = Sentry::findGroupByName('Publisher');
		if ( $user->inGroup($publisher) )
			$isPublisher = true;

		return $isPublisher;
	}

	public static function getUsersWithContent( $exceptId, $paginate )
	{
		return static::with(['pages' => function($query) { $query->whereIsLatest(1)->whereIsDeleted(0); }])->with('posts')/*->whereNotIn('id', [$exceptId])*/->paginate($paginate);
	}

}
