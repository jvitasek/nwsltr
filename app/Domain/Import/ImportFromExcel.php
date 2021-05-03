<?php declare(strict_types = 1);

namespace App\Domain\Import;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\RecipientGroup;
use App\Model\Database\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Http\FileUpload;
use Nette\Utils\ArrayHash;
use Nette\Utils\Validators;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

final class ImportFromExcel
{

	private const EMAIL_COLUMN = 0;
	private const FIRST_NAME_COLUMN = 1;
	private const LAST_NAME_COLUMN = 2;

	/** @var FileUpload */
	private FileUpload $file;

	/** @var RecipientGroup */
	private RecipientGroup $recipientGroup;

	/** @var bool */
	private bool $replaceCurrentRecipients;

	/** @var Account */
	private Account $account;

	/** @var EntityManager */
	private EntityManager $em;

	/**
	 * ImportFromExcel constructor.
	 *
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function execute(): bool
	{
		try {
			$filetype = IOFactory::identify((string) $this->file);
			$reader = IOFactory::createReader($filetype);
			$spreadsheet = $reader->load((string) $this->file);
		} catch (Exception $readerException) {
			return false;
		}

		if ($this->replaceCurrentRecipients) {
			$this->recipientGroup->setRecipients(new ArrayCollection());
			$this->em->persist($this->recipientGroup);
			$this->em->flush();
		}

		$sheet = $spreadsheet->getActiveSheet();

		for ($row = 1; $row <= $sheet->getHighestRow(); $row++) {
			$data = $sheet->rangeToArray('A' . $row . ':' . $sheet->getHighestColumn() . $row, null, true, false);

			if (isset($data[0][self::EMAIL_COLUMN])) {
				$email = trim($data[0][self::EMAIL_COLUMN]);
				$firstName = isset($data[0][self::FIRST_NAME_COLUMN]) ? trim($data[0][self::FIRST_NAME_COLUMN]) : null;
				$lastName = isset($data[0][self::LAST_NAME_COLUMN]) ? trim($data[0][self::LAST_NAME_COLUMN]) : null;

				if (Validators::isEmail($email)) {
					$recipient = $this->em->getRepository(Recipient::class)->findOneBy([
						'email' => $email,
						'account' => $this->account,
					]);

					if (!$recipient) {
						$recipient = new Recipient();
						$recipient->setEmail($email);
						$recipient->setSubscribed(true);
						$recipient->setAccount($this->account);
						$recipient->setFirstName($firstName);
						$recipient->setLastName($lastName);
						$this->em->persist($recipient);
					}

					if (!$recipient->getRecipientGroups()->contains($this->recipientGroup)) {
						$recipient->addRecipientGroup($this->recipientGroup);
						$this->recipientGroup->addRecipient($recipient);
						$this->em->persist($this->recipientGroup);
					}
				}
			}
		}

		$this->em->flush();

		return true;
	}

	public function getFile(): FileUpload
	{
		return $this->file;
	}

	public function setFile(FileUpload $file): void
	{
		$this->file = $file;
	}

	public function getRecipientGroup(): RecipientGroup
	{
		return $this->recipientGroup;
	}

	public function setRecipientGroup(RecipientGroup $recipientGroup): void
	{
		$this->recipientGroup = $recipientGroup;
	}

	public function isReplaceCurrentRecipients(): bool
	{
		return $this->replaceCurrentRecipients;
	}

	public function setReplaceCurrentRecipients(bool $replaceCurrentRecipients): void
	{
		$this->replaceCurrentRecipients = $replaceCurrentRecipients;
	}

	public function getAccount(): Account
	{
		return $this->account;
	}

	public function setAccount(Account $account): void
	{
		$this->account = $account;
	}

}