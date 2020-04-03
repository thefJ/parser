<?php
declare(strict_types = 1);

namespace App\Command\Parser\Strategy;

use App\Entity\Client;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Parses client logs
 */
class ClientParserStrategy extends BaseParserStrategy
{
    /** @var string */
    private const NAME = 'clients';
    /** @var int */
    private const FIELDS_COUNT = 3;
    /** @var int */
    private const IP_KEY = 0;
    /** @var int */
    private const BROWSER_KEY = 1;
    /** @var int */
    private const OS_KEY = 2;

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $this->entityManager->getRepository(Client::class)->truncate();

        $file = new \SplFileObject($this->getLogPath());
        while ($file->valid()) {
            $data = $file->fgetcsv('|');
            if (\count($data) !== static::FIELDS_COUNT) {
                continue;
            }
            $client = new Client();
            $client->setIp($data[static::IP_KEY]);
            $client->setBrowser($data[static::BROWSER_KEY]);
            $client->setOs($data[static::OS_KEY]);
            $this->entityManager->persist($client);
            try {
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                $this->managerRegistry->resetManager();
            }
            $this->entityManager->clear();
        }
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return self::NAME;
    }
}
