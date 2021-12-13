<?php
declare(strict_types=1);

namespace App\Modules\Front\Presenters;


use App\Modules\Front\Forms;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;

class HomepagePresenter extends BasePresenter
{
	/** @var DateTime */
	private $dateTime;

	/** @var bool */
	private $newsletterFormSubmitted = false;

	/** @var Forms\NewsletterFormFactory */
	private $newsletterFormFactory;


	/**
	 * HomepagePresenter constructor.
	 * @param Forms\NewsletterFormFactory $newsletterFormFactory
	 */
	public function __construct(Forms\NewsletterFormFactory $newsletterFormFactory)
	{
		$this->newsletterFormFactory = $newsletterFormFactory;
	}


	public function handleActualiseDateTime(): void
	{
		$this->dateTime = new DateTime;
		if ($this->isAjax()) {
			$this->redrawControl('dateTimeSnippet');
		}
	}


	public function renderDefault(): void
	{
		if ($this->dateTime === null) {
			$this->dateTime = new DateTime;
		}
		$this->template->dateTime = $this->dateTime;
		$this->template->newsletterFormSubmitted = $this->newsletterFormSubmitted;
	}


	protected function createComponentNewsletterForm(): Form
	{
		return $this->newsletterFormFactory->create(function (): void {
			$this->newsletterFormSubmitted = true;
			if ($this->isAjax()) {
				$this->redrawControl('newsletterSnippet');
			} else {
				$this->redirect('this');
			}
		});
	}
}
