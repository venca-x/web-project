<?php

declare(strict_types=1);

namespace App\Modules\Front\Forms;

use Nette;
use Nette\Application\UI\Form;

final class NewsletterFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;


	public function __construct(FormFactory $factory)
	{
		$this->factory = $factory;
	}


	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->getElementPrototype()->class('ajax');

		$form->addEmail('email', 'E-mail:')
			->setRequired('Please enter your e-mail');

		$form->addSubmit('send', 'Submit');

		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void {
			$onSuccess();
		};

		return $form;
	}
}
