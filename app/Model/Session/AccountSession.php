<?php declare(strict_types = 1);

namespace App\Model\Session;

use App\Model\Database\Entity\Account;
use App\Model\Database\EntityManager;
use App\Model\Database\Repository\UserRepository;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Security\User;

final class AccountSession
{

	private const SECTION_NAME = 'accountSession';

	private SessionSection $accountSessionSection;
	private Session $session;
	private User $user;
	private EntityManager $entityManager;

	public function __construct(Session $session, User $user, EntityManager $entityManager)
	{
		$this->session = $session;
		$this->accountSessionSection = $this->session->getSection(self::SECTION_NAME);
		$this->user = $user;
		$this->entityManager = $entityManager;
	}

	public function set(int $accountId): void
	{
		$this->accountSessionSection->offsetSet('id', $accountId);
	}

	public function get(): ?int
	{
		if ($this->accountSessionSection->offsetGet('id')) {
			return $this->accountSessionSection->offsetGet('id');
		} else {
			// no account has been manually set
			// we set it automatically

			if ($this->user->isLoggedIn()) {
				$user = $this->entityManager->getRepository(\App\Model\Database\Entity\User::class)->find($this->user->getId());

				if ($user && $user->getAccounts()->count() > 0) {
					/** @var Account $firstAccount */
					$firstAccount = $user->getAccounts()->first();
					$this->set($firstAccount->getId());

					return $firstAccount->getId();
				}
			}
		}

		return null;
	}

	/**
	 * Removes the whole session.
	 */
	public function empty(): void
	{
		$this->accountSessionSection->remove();
	}

}