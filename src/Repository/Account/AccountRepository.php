<?php

namespace App\Repository\Account;

use App\Entity\Account\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param Account $account
     *
     * @throws ORMException
     */
    public function save(Account $account): void
    {
        $this->_em->persist($account);
    }

    /**
     * @param string $externalId
     *
     * @return Account
     *
     * @throws NonUniqueResultException
     */
    public function findByExternalId(string $externalId): Account
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.externalId = :val')
            ->setParameter('val', $externalId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $email
     *
     * @return Account
     *
     * @throws NonUniqueResultException
     */
    public function findByEmail(string $email): Account
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.email = :val')
            ->setParameter('val', $email)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
