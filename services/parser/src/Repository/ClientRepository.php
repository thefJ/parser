<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Client;

use App\Traits\TruncateRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    use TruncateRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getClientsVisitStatistic(int $offset, int $limit): array
    {
        $connection = $this->getEntityManager()->getConnection();
        return $connection->query(
            \sprintf('
                SELECT
                c.ip,
                c.browser,
                c.os,
                vf.referrer,
                vl.url,
                v.url_count
                FROM client c
                LEFT JOIN (SELECT ip, count(DISTINCT(url)) url_count FROM visit GROUP BY ip) v on v.ip = c.ip
                LEFT JOIN (SELECT  ip, MIN(date) MinDate FROM visit GROUP BY ip) MinDates ON c.ip = MinDates.ip
                LEFT JOIN visit vf ON  MinDates.ip = vf.ip AND MinDates.MinDate = vf.date
                LEFT JOIN (SELECT  ip, MAX(date) MaxDate FROM visit GROUP BY ip) MaxDates ON c.ip = MaxDates.ip
                LEFT JOIN visit vl ON  MaxDates.ip = vl.ip AND MaxDates.MaxDate = vl.date
                GROUP BY c.id, vf.referrer, vl.url, v.url_count
                LIMIT %d OFFSET %d
                ',
                $limit,
                $offset
            )
        )->fetchAll();
    }

    /**
     * @return int
     */
    public function getClientsVisitStatisticCount(): int
    {
        $connection = $this->getEntityManager()->getConnection();
        return (int)$connection->query('SELECT COUNT(c.id) FROM client c')->fetchColumn();
    }

}
