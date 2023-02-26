<?php

namespace App\Command;

use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use JsonMachine\Items;

#[AsCommand(
    name: 'data:json-metrics',
    description: 'Calculate fields metadata and metrics for a JSON file',
)]
class JsonDataFileMetricsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'JSON data file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Check file existence
        $file = $input->getArgument('file');
        if (!file_exists($file)) {
            $io->error("File $file does not exist");
            return Command::FAILURE;
        }
        $io->info("Starting processing of $file");

        // Aggregate fields names, types and sizes
        $items = Items::fromFile($file, ['decoder' => new ExtJsonDecoder(true)]);
        $fields = [];
        $io->info("Total items count : " . iterator_count($items));
        $progress = new ProgressBar($output);
        foreach ($progress->iterate($items) as $item) {
            foreach (array_keys($item) as $property) {
                // Initial property set
                if (!array_key_exists($property, $fields)) {
                    $fields[$property] = [
                        'name' => $property,
                        'available_types' => [],
                        'null_values' => false,
                        "empty_values" => false,
                        'missing_values' => false,
                        'max_size' => null
                    ];
                }

                // Add detected type
                if (!in_array($type = get_debug_type($item[$property]), $fields[$property]['available_types'])
                    && 'null' !== $type) {
                    $fields[$property]['available_types'][] = $type;
                    asort($fields[$property]['available_types']);
                }

                // Permanently set nullable if null detected
                if (false === $fields[$property]['null_values'] && null === $item[$property]) {
                    $fields[$property]['null_values'] = true;
                }

                // Permanently set empty if empty string detected
                if (false === $fields[$property]['empty_values'] && '' === $item[$property]) {
                    $fields[$property]['empty_values'] = true;
                }

                // Record detected max string size
                $fields[$property]['max_size'] = is_string($item[$property]) ?
                    max($fields[$property]['max_size'], strlen($item[$property]))
                    : $fields[$property]['max_size'];

                // Check for missing props (heterogeneous data set)
                if (false === $fields[$property]['missing_values']) {
                    foreach (array_keys($fields) as $missingCandidate) {
                        if (!array_key_exists($missingCandidate, $item)) {
                            $fields[$property]['missing_values'] = true;
                            break;
                        }
                    }
                }
            }
        }

        // Output the results table
        $table = new Table($output);
        $table
            ->setHeaders([
                'Name',
                'Types',
                'Has null values',
                'Has empty Values',
                'Missing',
                'Max length'
            ])
            ->setRows(array_map(static function ($field) {
                return [
                    $field['name'],
                    implode(', ', $field['available_types']),
                    true === $field['null_values'] ? 'yes': 'no',
                    true === $field['empty_values'] ? 'yes': 'no',
                    true === $field['missing_values'] ? 'yes': 'no',
                    $field['max_size'] ?? 'n/a'
                ];
            },$fields));
        $table->render();

        // Done
        $io->success('Command finished');
        return Command::SUCCESS;
    }
}
