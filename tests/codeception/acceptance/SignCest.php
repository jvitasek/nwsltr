<?php

class SignCest
{

	public function signInPageWorks(AcceptanceTester $I): void
	{
		$I->amOnPage('/sign/in');
		$I->see('Sign In');
    }

	public function authenticationWorks(AcceptanceTester $I): void
	{
		$I->login();
    }
}
