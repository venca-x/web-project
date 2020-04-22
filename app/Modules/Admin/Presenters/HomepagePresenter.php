<?php
declare(strict_types=1);

namespace App\Modules\Admin\Presenters;

use App;
use Nette\Application\UI\Form;

class HomepagePresenter extends BasePresenter
{
	/** @var App\Modules\Admin\Forms\SignInFormFactory */
	private $signInFormFactory;


	public function __construct(App\Modules\Admin\Forms\SignInFormFactory $signInFactory)
	{
		$this->signInFormFactory = $signInFactory;
	}


	public function actionDefault(): void
	{
		if ($this->getUser()->isLoggedIn()) {
			$this->redirect('Dashboard:default');
		}
	}


	public function actionLogout(): void
	{
		$this->getUser()->logout();
		$this->flashMessage('Hezký den šéfe :-)', 'alert-warning');
		$this->redirect('Homepage:default');
	}


	protected function createComponentSignInForm(): Form
	{
		return $this->signInFormFactory->create(function (): void {
			$this->redirect('Dashboard:');
		});
	}
}
