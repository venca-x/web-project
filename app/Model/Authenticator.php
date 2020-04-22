<?php

declare(strict_types=1);

namespace App\Model;

use App;
use App\Model;
use Nette;
use Nette\Security;

/**
 * Users authenticator
 */
class Authenticator implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	/** @var Model\User */
	private $userRepository;


	/**
	 * @param Nette\DI\Container $context
	 */
	public function __construct(App\Model\User $userRepository)
	{
		$this->userRepository = $userRepository;
	}


	/**
	 * Performs an authentication
	 * @param array $credentials
	 * @return Security\IIdentity
	 * @throws Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		[$email, $password] = $credentials;
		//search user by email
		$row = $this->userRepository->findByEmail($email);

		if (!$row) {
			throw new Security\AuthenticationException('Uživatel nenalezen', self::IDENTITY_NOT_FOUND);
		}

		if (password_verify($password, $row->password)) {
			return $this->createIdentity($row);
		} else {
			throw new Security\AuthenticationException('Špatné heslo', self::INVALID_CREDENTIAL);
		}
	}


	/**
	 * Create user identity
	 * @param \Nette\Database\Table\ActiveRow $userRow
	 * @return \Nette\Security\Identity
	 */
	private function createIdentity($userRow)
	{
		$userArray = $userRow->toArray();
		unset($userArray['password']);//remove password from identity
		return new Security\Identity($userRow->id, $userRow->role, $userArray);
	}


	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public function calculateHash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}
}
