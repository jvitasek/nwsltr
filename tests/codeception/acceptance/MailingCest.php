<?php

class MailingCest
{

	public function datagridWorks(AcceptanceTester $I): void
	{
		$I->login();
		$I->click('Mailings');
		$I->see('Create Mailing');
	}

	public function editorWorks(AcceptanceTester $I): void
	{
		$this->datagridWorks($I);
		$I->click('Create Mailing');
		$I->seeCurrentUrlMatches('~^/editor/default/(\d+)~');
	}
}
