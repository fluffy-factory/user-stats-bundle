<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLinesArchives;

/**
 * @method UserStatsLinesArchives|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStatsLinesArchives|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStatsLinesArchives[]    findAll()
 * @method UserStatsLinesArchives[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStatsLinesArchivesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatsLinesArchives::class);
    }

}
