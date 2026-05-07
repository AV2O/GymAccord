<?php

namespace App\Command;

use App\Service\ImageOptimizerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(name: 'app:optimize-images', description: 'Convertit les images en WebP')]
class OptimizeImagesCommand extends Command
{
    public function __construct(
        private ImageOptimizerService $optimizer,
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dir = $this->projectDir . '/public/images/photo';
        $this->optimizer->convertDirectory($dir);
        $output->writeln('✅ Images converties en WebP !');

        return Command::SUCCESS;
    }
}
