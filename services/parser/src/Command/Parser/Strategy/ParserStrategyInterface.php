<?php
declare(strict_types = 1);

namespace App\Command\Parser\Strategy;

/**
 * Parser stategy interface
 */
interface ParserStrategyInterface
{
    /** @var string */
    public const TAG = 'app.parser_strategy';

    /**
     * Parses log file
     */
    public function parse(): void;

    /**
     * Validates log files
     *
     * @return bool
     */
    public function isValid(): bool;
}
