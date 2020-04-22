<?php
declare(strict_types=1);

namespace App\Modules\Admin\Presenters;


abstract class SecurePresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();

		if (!$this->isAdmin()) {
			$this->redirect(':Front:Homepage:');
		}
	}


	/**
	 * Is user admin?
	 * @return bool
	 */
	protected function isAdmin(): bool
	{
		if ($this->getUser()->isInRole('admin') == true) {
			return true;
		} else {
			return false;
		}
	}
}
