<?php

declare(strict_types=1);

namespace App\Model;

use Nette;

class User extends Repository
{
	/** @var string */
	protected $tableName = 'user';


	/**
	 * Search user by email
	 * @param $email
	 * @return Nette\Database\IRow|Nette\Database\Table\ActiveRow|null
	 */
	public function findByEmail($email)
	{
		return $this->findAll()->where('email', $email)->fetch();
	}


	/**
	 * Is email unique? Unique from logged user (loged email can be same as chnaged)
	 * @param $nick
	 * @param $userId ID logged user
	 * @return bool
	 */
	public function isEmailUnique($nick, $userId)
	{
		$count = $this->findAll()->where('nick = ? AND id != ?', $nick, $userId)->count('*');
		return $count == 0;
	}
}
