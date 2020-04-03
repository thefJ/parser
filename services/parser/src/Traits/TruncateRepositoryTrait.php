<?php
declare(strict_types = 1);

namespace App\Traits;

/**
 * Adds truncate logic to repository
 */
trait TruncateRepositoryTrait
{
    public function truncate(): void
    {
        $connection = $this->getEntityManager()->getConnection();
        $tableName = $this->getClassMetadata()->getTableName();
        $connection->exec(\sprintf('TRUNCATE TABLE %s', $tableName));
        $connection->exec(\sprintf('ALTER SEQUENCE %s_id_seq RESTART WITH 1', $tableName));
    }
}
