<?php

namespace FluffyFactory\Bundle\UserStatsBundle\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use FluffyFactory\Bundle\UserStatsBundle\Entity\UserStatsLines;

/**
 * @method UserStatsLines|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserStatsLines|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserStatsLines[]    findAll()
 * @method UserStatsLines[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserStatsLinesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatsLines::class);
    }

    /**
     * @param $user
     * @param DateTime $begin
     * @param DateTime $end
     * @return int|mixed|string
     */
    public function findByPeriod(User $user, DateTime $begin, DateTime $end)
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->andWhere('usl.createdAt BETWEEN :begin AND :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->orderBy('usl.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param User $user
     * @return int|mixed|string
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
            ;
    }
}
