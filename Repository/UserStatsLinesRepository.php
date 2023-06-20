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
     * @param User $user
     * @param DateTime $begin
     * @param DateTime $end
     * @param int $maxResult
     * @return array
     */
    public function findByPeriod(User $user, DateTime $begin, DateTime $end, int $maxResult = 0): array
    {
        $query = $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->andWhere('usl.createdAt BETWEEN :begin AND :end')
            ->setParameter('user', $user)
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->orderBy('usl.createdAt', 'DESC');

        if ($maxResult) {
            $query->setMaxResults($maxResult);
        }

        return $query->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param string $route
     * @return array
     */
    public function findIfUserVisited(User $user, string $route): array
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->andWhere('usl.route = :route')
            ->setParameter('user', $user)
            ->setParameter('route', $route)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findBySession(User $user): array
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.user = :user')
            ->andWhere('usl.sessionId IS NOT NULL')
            ->groupBy('usl.sessionId')
            ->addGroupBy('usl.id')
            ->setParameter('user', $user)
            ->orderBy('usl.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DateTime $dateToArchive
     * @return array
     */
    public function findToArchive(DateTime $dateToArchive): array
    {
        return $this->createQueryBuilder('usl')
            ->andWhere('usl.createdAt < :dateToArchive')
            ->setParameter('dateToArchive', $dateToArchive)
            ->getQuery()
            ->getResult();
    }
}
