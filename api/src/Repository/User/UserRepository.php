<?php

namespace App\Repository\User;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements IUserRepository 
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    public function save(User $user): void
    {
      $this->getEntityManager()->persist($user);
      $this->getEntityManager()->flush();
    }

    public function findByEmail(string $email): ?User
    {
      return $this->findOneBy(['email' => $email]);
    }

    public function verifiedPhone($email): void
    {
      $user = $this->findByEmail($email);
      $user->setPhoneNumberVerified(true);
      $this->getEntityManager()->persist($user);
      $this->getEntityManager()->flush();
    }

    public function verifiedEmail($email): void
    {
      $user = $this->findByEmail($email);
      $user->setEmailVerified(true);
      $this->getEntityManager()->persist($user);
      $this->getEntityManager()->flush();
    }

}
