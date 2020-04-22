<?php

declare(strict_types=1);

namespace App\Modules\Admin\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


final class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addEmail('email', 'E-mail')
			->setRequired('Zadejte Váš e-mail')
			->setHtmlAttribute('placeholder', 'E-mail');
		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadejte Vaše heslo')
			->setHtmlAttribute('placeholder', 'Heslo');
		$form->addSubmit('login', 'Přihlásit')
			->setHtmlAttribute('class', 'btn btn-primary w-100');
		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			try {
				$this->user->login($values->email, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError($e->getMessage());
				return;
			}
			$onSuccess();
		};

		return $form;
	}
}
