<?php

declare(strict_types=1);

namespace App\Modules\Front\Forms;

use Nette;
use Nette\Application\UI\Form;
use VencaX;


final class FormFactory
{
	use Nette\SmartObject;

	public function create(): Form
	{
		$form = new Form;
		$form->setRenderer(new VencaX\NetteFormRenderer\BootstrapRendererV4);
		return $form;
	}
}
