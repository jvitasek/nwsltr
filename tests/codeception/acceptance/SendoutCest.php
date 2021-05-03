<?php

class SendoutCest
{

	public function datagridWorks(AcceptanceTester $I): void
	{
		$I->login();
		$I->click('Sendouts');
		$I->seeCurrentUrlEquals('/sendout/list');
	}
}
