<?php

class RecipientGroupCest
{

	public function datagridWorks(AcceptanceTester $I): void
	{
		$I->login();
		$I->click('Groups');
		$I->seeCurrentUrlEquals('/recipient-group/list');
		$I->see('Create Group');
	}
}
