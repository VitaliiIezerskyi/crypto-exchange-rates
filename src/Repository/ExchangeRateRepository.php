<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function findRatesForLast24Hours(string $currencyPair, \DateTimeImmutable $since): array
    {
        return $this->createQueryBuilder('er')
            ->andWhere('er.currencyPair = :currencyPair')
            ->andWhere('er.createdAt >= :since')
            ->setParameter('currencyPair', $currencyPair)
            ->setParameter('since', $since)
            ->orderBy('er.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRatesForDay(string $currencyPair, \DateTimeInterface $date): array
    {
        $startOfDay = (clone $date)->setTime(0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);

        return $this->createQueryBuilder('er')
            ->andWhere('er.currencyPair = :currencyPair')
            ->andWhere('er.createdAt >= :startOfDay')
            ->andWhere('er.createdAt <= :endOfDay')
            ->setParameter('currencyPair', $currencyPair)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->orderBy('er.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
