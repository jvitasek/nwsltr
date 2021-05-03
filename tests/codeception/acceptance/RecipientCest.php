<?php

class RecipientCest
{

	public function datagridWorks(AcceptanceTester $I): void
	{
		$I->login();
		$I->click('Recipients');
		$I->seeCurrentUrlEquals('/recipient/list');
		$I->see('Create Recipient');
	}
}
