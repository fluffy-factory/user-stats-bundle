<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Command;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLinesArchives;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[AsCommand(
    name: 'make:user:stats:archive',
    description: 'Archive all user data in another table',
)]
class ArchiveUserStatsCommand extends Command
{
    private OutputInterface $output;

    protected function configure()
    {
        $this->setName('make:user:stats:archive');
        $this->setDescription('Archive all user data in another table');
    }

    public function __construct(private EntityManagerInterface $em, private ContainerBagInterface $containerBag)
    {
        parent::__construct();
    }

    /**
     * Send reminder email to user when their gift entry is coming to an end
     * Launch this command once a day with cron on production
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;
        $this->archiveData();

        return 0;
    }

    /**
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function archiveData() :void
    {
        $this->output->writeln("Begin get data");

        $dateToArchive = (new DateTime())->modify("-" . $this->containerBag->get('fluffy_user_stats')['max_month_before_archive'] . " months");

        $userStatsLinesToArchives = $this->em->getRepository(UserStatsLines::class)->findToArchive($dateToArchive);

        $progressBar = new ProgressBar($this->output, count($userStatsLinesToArchives));
        $progressBar->setFormat('debug');
        $progressBar->setProgressCharacter('<fg=blue>></>');
        $progressBar->setBarCharacter('<fg=green>-</>');

        $this->output->writeln( "Begin transfert data");
        /** @var UserStatsLines $userStatsLinesToArchive */
        foreach ($userStatsLinesToArchives as $key => $userStatsLine) {
            $progressBar->advance();

            $userStatsLinesArchive = new UserStatsLinesArchives();

            $userStatsLinesArchive
                ->setUser($userStatsLine->getUser())
                ->setBrowser($userStatsLine->getBrowser())
                ->setCreatedAt($userStatsLine->getCreatedAt())
                ->setRoute($userStatsLine->getRoute())
                ->setSessionId($userStatsLine->getSessionId())
                ->setUrl($userStatsLine->getUrl());

            $this->em->persist($userStatsLinesArchive);
            $this->em->remove($userStatsLine);

            if ($key % 50000 === 0) {
                $this->em->flush();
            }
        }

        $this->em->flush();
        $progressBar->finish();
        $this->output->writeln( "\nEnd transfert data");
    }
}
