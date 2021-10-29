<?php

declare(strict_types=1);

namespace App\Repository;

use App;
use App\Repository;
use Nette;
use Nette\Security;
use Nette\Security\IIdentity;


/**
 * Users authenticator
 */
class Authenticator implements Nette\Security\Authenticator
{
	use Nette\SmartObject;

	/** @var Repository\User */
	private $userRepository;


	public function __construct(App\Repository\User $userRepository)
	{
		$this->userRepository = $userRepository;
	}


	/**
	 * Performs an authentication
	 * @param string $user email
	 * @param string $password
	 * @return IIdentity

	 * @throws Security\AuthenticationException
	 */
	public function authenticate(string $user, string $password): IIdentity
	{
		$row = $this->userRepository->findByEmail($user);

		if (!$row) {
			throw new Security\AuthenticationException('Uživatel nenalezen', self::IDENTITY_NOT_FOUND);
		}

		if (password_verify($password, $row->password)) {
			return $this->createIdentity($row);
		} else {
			throw new Security\AuthenticationException('Špatné heslo', self::INVALID_CREDENTIAL);
		}

		return $this->createIdentity($row);
	}


	/**
	 * Create user identity
	 * @param Nette\Database\Table\ActiveRow $userRow
	 * @return Nette\Security\Identity
	 */
	private function createIdentity($userRow)
	{
		$userArray = $userRow->toArray();
		unset($userArray['password']); //remove password from identity
		return new Security\SimpleIdentity($userRow->id, [$userRow->role], $userArray);
	}


	/**
	 * Computes salted password hash.
	 * @param string
	 * @return string
	 */
	public static function calculateHash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}
