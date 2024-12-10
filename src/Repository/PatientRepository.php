<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 *
 * @method Patient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Patient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Patient[]    findAll()
 * @method Patient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }
    public function countConsultationsByPatient(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.id', 'p.name', 'COUNT(c.id) AS consultation_count')
            ->leftJoin('p.consultations', 'c')  // Assurez-vous que 'consultations' est bien le nom de la relation
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }
}
