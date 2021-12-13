<?php

declare(strict_types=1);

namespace App\Repository;

use Nette;

class User extends Repository
{
	/** @var string */
	protected $tableName = 'user';


	/**
	 * Search user by email
	 * @param string $email
	 * @return Nette\Database\Table\ActiveRow|null
	 */
	public function findByEmail($email)
	{
		return $this->findAll()->where('email', $email)->fetch();
	}


	/**
	 * Is email unique? Unique from logged user (loged email can be same as chnaged)
	 * @param string $nick
	 * @param int $userId logged userId
	 * @return bool
	 */
	public function isEmailUnique($nick, $userId)
	{
		return $this->findAll()->where('nick = ? AND id != ?', $nick, $userId)->count('*') == 0;
	}
}
