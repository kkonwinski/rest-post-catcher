<?php

namespace App\Command;

use App\Controller\ArticleController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'api:get-posts',
    description: 'Get posts and authors',
)]
class ApiGetPostsCommand extends Command
{

    private ArticleController $articleController;

    public function __construct(ArticleController $articleController, string $name = null)
    {
        parent::__construct($name);

        $this->articleController = $articleController;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->articleController->addArticle();
        $io->success('You download all articles');

        return Command::SUCCESS;
    }
}
