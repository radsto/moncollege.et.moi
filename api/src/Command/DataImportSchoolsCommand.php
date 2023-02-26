<?php

namespace App\Command;

use App\Entity\School\School;
use App\Normalizer\SchoolNormalizer;
use App\Repository\School\SchoolRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[AsCommand(
    name: 'data:import-schools',
    description: 'Import schools from JSON data file',
)]
class DataImportSchoolsCommand extends Command
{
    private SchoolRepository $schoolRepository;
    private EntityManagerInterface $em;

    public function __construct(
        SchoolRepository $schoolRepository,
        EntityManagerInterface $em
    )
    {
        $this->schoolRepository = $schoolRepository;
        $this->em = $em;

        parent::__construct();
    }

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

        // Prep
        $serializer = new Serializer(
            normalizers: [new SchoolNormalizer(new ObjectNormalizer())],
            encoders: [new JsonEncoder()]
        );
        $progress = new ProgressBar($output);

        // Counters
        $count = 0;
        $new = 0;
        $updated = 0;
        $deleted = 0;

        // Get all existing schools ids
        $qb = $this->schoolRepository->createQueryBuilder('s');
        $qb->select('s.numero_uai');
        $existingSchoolsIds = $qb->getQuery()->execute(hydrationMode: AbstractQuery::HYDRATE_OBJECT);

        // Create or update the schools
        $io->info("Starting creating / updating schools from file : $file");
        $items = Items::fromFile($file, ['decoder' => new ExtJsonDecoder(true)]);
        $schoolsIds = [];
        foreach ($progress->iterate($items) as $item) {
            $uai = $item['numero_uai'];
            $schoolsIds[] = $uai;

            if (!$school = $this->schoolRepository->find($uai)) {
                $school = new School();
                $new++;
            } else {
                $updated++;
            }

            $school = $serializer->deserialize(
                json_encode($item),
                School::class,
                'json',
                [
                    'object_to_populate' => $school
                ]
            );

            $this->em->persist($school);
            if (0 === $count % 100) {
                $this->em->flush();
            }
        }

        // Delete schools not in data file
        $io->info("Starting deleting schools not in file : $file");
        $toDelete = array_diff($existingSchoolsIds, $schoolsIds);
        $progress = new ProgressBar($output);
        foreach ($progress->iterate($toDelete) as $id) {
            $school = $this->schoolRepository->find($id);
            $this->em->remove($school);
            $deleted++;
        }
        $this->em->flush();

        // Log
        $io->info("Import results : $new created, $updated updated, $deleted deleted");

        // Done
        $io->success('Command finished');
        return Command::SUCCESS;
    }
}
