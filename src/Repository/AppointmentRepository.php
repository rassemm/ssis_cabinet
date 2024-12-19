<?php

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findByDoctorAndDate(Doctor $doctor, \DateTime $date): array
    {
        // Créez le début et la fin de la journée
        $startOfDay = (clone $date)->setTime(0, 0, 0);
        $endOfDay = (clone $date)->setTime(23, 59, 59);
    
        // Construire la requête
        return $this->createQueryBuilder('a')
            ->where('a.doctor = :doctor')
            ->andWhere('a.date BETWEEN :start AND :end')
            ->setParameter('doctor', $doctor)
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->getQuery()
            ->getResult();
    }
    
}
