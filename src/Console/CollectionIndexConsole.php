<?php

namespace GovInfo\Console;

use GuzzleHttp\Client;
use GovInfo\Api;
use GovInfo\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console application for pulling collections
 */
class CollectionIndexConsole extends Command
{
    use ApiKeyTrait;

    private $apiKey;

    public function configure()
    {
        $this
            ->setName('collection:index')
            ->setDescription('Shows all collections')
            ->defineApiKeyFromFile();

        if (empty($this->apiKey)) {
            $this->addArgument('apiKey', InputArgument::REQUIRED, 'Your API Key');
        }
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $apiKey = $this->getApiKey($input);

        $api = new Api(new Client(), $apiKey);
        $collection = new Collection($api);
        $result = $collection->index();

        $table = new Table($output);
        $table->setHeaders(['collectionCode', 'collectionName', 'packageCount', 'granuleCount']);

        foreach ($result['collections'] as $row) {
            $table->addRow(
                array_values($row)
            );
        }

        $table->render();

        return 0;
    }
}