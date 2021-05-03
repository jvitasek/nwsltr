<?php

class DashboardCest
{

	public function homepageWorks(AcceptanceTester $I): void
	{
		$I->login();
		$I->amOnPage('/');
		$I->see('Dashboard');
	}

}
