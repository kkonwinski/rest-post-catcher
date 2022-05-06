<?php

namespace App\Command;

use App\Service\ArticleApiService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'api:get-posts',
    description: 'Get posts and authors',
)]
class ApiGetPostsCommand extends Command
{
    private ArticleApiService $apiService;

    public function __construct(ArticleApiService $apiService,string $name = null)
    {
        parent::__construct($name);
        $this->apiService = $apiService;

    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->apiService->getArticle();
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
