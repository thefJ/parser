<?php
declare(strict_types = 1);

namespace App\Command\Parser\Strategy;

use App\Entity\Visit;

/**
 * Parses visit logs
 */
class VisitParserStrategy extends BaseParserStrategy
{
    /** @var string */
    private const NAME = 'visits';
    /** @var int */
    private const FIELDS_COUNT = 5;
    /** @var int */
    private const DATE_KEY = 0;
    /** @var int */
    private const TIME_KEY = 1;
    /** @var int */
    private const IP_KEY = 2;
    /** @var int */
    private const REFERRER_KEY = 3;
    /** @var int */
    private const URL_KEY = 4;
    /** @var int */
    public const BATCH_SIZE = 1000;

    /**
     * @inheritDoc
     */
    public function parse(): void
    {
        $this->entityManager->getRepository(Visit::class)->truncate();
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        $file = new \SplFileObject($this->getLogPath());
        while ($file->valid()) {
            $data = $file->fgetcsv('|');
            if (\count($data) !== static::FIELDS_COUNT) {
                continue;
            }
            $dateTime = new \DateTimeImmutable(\sprintf('%s %s', $data[static::DATE_KEY], $data[static::TIME_KEY]));
            $visit = new Visit();
            $visit->setDate($dateTime);
            $visit->setIp($data[static::IP_KEY]);
            $visit->setReferrer($data[static::REFERRER_KEY]);
            $visit->setUrl($data[static::URL_KEY]);
            $this->entityManager->persist($visit);
            if (($file->key() % self::BATCH_SIZE) === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /**
     * @inheritDoc
     */
    public static function getName(): string
    {
        return self::NAME;
    }
}
