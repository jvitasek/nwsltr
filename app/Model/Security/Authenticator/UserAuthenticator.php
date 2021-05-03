<?php declare(strict_types = 1);

namespace App\Model\Security\Authenticator;

use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Model\Security\Passwords;
use App\Model\Session\AccountSession;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;

final class UserAuthenticator implements Authenticator
{

	private EntityManager $em;
	private Passwords $passwords;

	public function __construct(
		EntityManager $em,
		Passwords $passwords
	)
	{
		$this->em = $em;
		$this->passwords = $passwords;
	}

	public function authenticate(string $email, string $password): IIdentity
	{
		/** @var User|null $user */
		$user = $this->em->getUserRepository()->findOneByEmail($email);

		if ($user === null) {
			throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
		} elseif (!$user->isActivated()) {
			throw new AuthenticationException('The user is not active.', self::INVALID_CREDENTIAL);
		} elseif (!$this->passwords->verify($password, $user->getPassword())) {
			throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
		} elseif ($user->getAccounts()->count() <= 0) {
			throw new AuthenticationException('The user has no account assigned.', self::INVALID_CREDENTIAL);
		}

		$user->changeLoggedAt();
		$this->em->flush();

		return $this->createIdentity($user);
	}

	protected function createIdentity(User $user): IIdentity
	{
		$this->em->flush();

		return $user->toIdentity();
	}

}