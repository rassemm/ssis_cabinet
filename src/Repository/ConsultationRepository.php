<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consultation>
 *
 * @method Consultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consultation[]    findAll()
 * @method Consultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }
    public function findConsultationsByDoctor()
    {
        return $this->createQueryBuilder('c')
            ->select('d.firstName AS doctor_name', 'COUNT(c.id) AS consultations_by_doctor')
            ->leftJoin('c.doctor', 'd')
            ->groupBy('d.id')
            ->orderBy('consultations_by_doctor', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findConsultationsByPatient()
    {
        return $this->createQueryBuilder('c')
            ->select('p.firstName AS patient_name', 'COUNT(c.id) AS consultations_by_patient')
            ->leftJoin('c.patient', 'p')
            ->groupBy('p.id')
            ->orderBy('consultations_by_patient', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
