<?php
declare(strict_types = 1);

namespace App\Interfaces;

/**
 * Базовый интерфейс для сервиса который имеет имя.
 */
interface NamedTaggedServiceInterface
{
    /**
     * Get service name
     *
     * @return string
     */
    public static function getName(): string;
}
