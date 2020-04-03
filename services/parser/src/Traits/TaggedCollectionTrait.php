<?php
declare(strict_types = 1);

namespace App\Traits;

use App\Interfaces\NamedTaggedServiceInterface;

/**
 * Трейт для тегированных коллекций, реализующий их функционал.
 */
trait TaggedCollectionTrait
{
    /** @var object[] */
    protected array $services = [];

    /**
     * Констуктор.
     *
     * @param iterable|null $services
     */
    public function __construct(?iterable $services = [])
    {
        if (!$services) {
            return;
        }

        $this->addServices($services);
    }

    /**
     * Возвращает сервис по его имени, работает только при наличии {@see NamedTaggedServiceInterface} на сервисах.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    public function get(string $name): ?object
    {
        return $this->services[$name] ?? null;
    }

    /**
     * Получить все сервисы.
     *
     * @return object[]
     */
    public function all(): array
    {
        return $this->services;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return \count($this->services);
    }

    /**
     * Собирает коллекцию.
     *
     * @param iterable $services
     */
    protected function addServices(iterable $services): void
    {
        foreach ($services as $service) {
            if ($service instanceof NamedTaggedServiceInterface) {
                $this->services[$service::getName()] = $service;
            } else {
                $this->services[] = $service;
            }
        }
    }
}
