<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    
    /**
     * findList
     *
     * @param  bool $isDone
     * @return array<Task>
     */
    public function findList(bool $isDone): array
    {        
        return $this
            ->createQueryBuilder('t')
            ->where('t.isDone = :isDone')
            ->setParameter('isDone', $isDone)
            ->orderBy('t.updatedAt', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;        
    }
}
