<?php
declare(strict_types = 1);

namespace App\Command\Parser\Strategy;

use App\Interfaces\NamedTaggedServiceInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Base logic for all parser stategies
 */
abstract class BaseParserStrategy implements ParserStrategyInterface, NamedTaggedServiceInterface
{
    /** @var string */
    private const PATH_TEMPLATE = '%s/logs/%s.log';

    protected ManagerRegistry        $managerRegistry;

    protected EntityManagerInterface $entityManager;

    private string                   $rootFolder;

    /**
     * @param ManagerRegistry        $managerRegistry
     * @param ParameterBagInterface  $parameterBag
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager   = $entityManager;
        $this->rootFolder      = $parameterBag->get('kernel.project_dir');
    }

    /**
     * @inheritDoc
     */
    public function isValid(): bool
    {
        return (new Filesystem())->exists($this->getLogPath());
    }

    /**
     * @return string
     */
    public function getLogPath(): string
    {
        return \sprintf(self::PATH_TEMPLATE, $this->rootFolder, static::getName());
    }
}
