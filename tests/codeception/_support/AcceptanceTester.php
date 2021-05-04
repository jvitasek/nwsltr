<?php

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{

	const EMAIL = 'admin@sample.com';
	const PASSWORD = 'secret';

	use _generated\AcceptanceTesterActions;

	public function login(): void
	{
		$this->amOnPage('/sign/in');
		$this->fillField('email', self::EMAIL);
		$this->fillField('password', self::PASSWORD);
		$this->click('Submit');
		$this->see('Dashboard');
	}

}
