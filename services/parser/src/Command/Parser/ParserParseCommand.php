<?php
declare(strict_types = 1);

namespace App\Command\Parser;

use App\Command\Parser\Strategy\ParserStrategyInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Parses logs files from /logs folder
 */
class ParserParseCommand extends Command
{
    protected static $defaultName = 'parser:parse';

    private ParserStrategyCollection $strategyCollection;

    /**
     * @param ParserStrategyCollection $strategyCollection
     */
    public function __construct(ParserStrategyCollection $strategyCollection)
    {
        parent::__construct();
        $this->strategyCollection = $strategyCollection;
    }

    protected function configure()
    {
        $this->setDescription('Parses logs files from /logs folder');
    }

    /**
     * Execute parse command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Log parsing starts');
        $io->newLine();
        $progressBar = new ProgressBar($io, $this->strategyCollection->count());
        /** @var ParserStrategyInterface $strategy */
        foreach ($this->strategyCollection->all() as $strategy) {
            if (!$strategy->isValid()) {
                continue;
            }
            try {
                $strategy->parse();
            } catch (\Exception $exception) {
                $io->error(sprintf('Parsing ended with errors for %s. Message: %s', $strategy::getName(), $exception->getMessage()));
            }
            $progressBar->advance();
        }
        $progressBar->finish();
        $io->success('Parsing have ended!');

        return 0;
    }
}
