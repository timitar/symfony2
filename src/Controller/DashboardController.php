<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager)
    {
        $users=$manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $ads=$manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
        $bookings=$manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
        $comments=$manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
     
        $bestAds=$manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.fistName, u.lastName, u.picture
             FROM App\Entity\Comment c
             JOIN c.ad a
             JOIN a.author u
             GROUP BY a
             ORDER BY note DESC'
        )->setMaxResults(5)
        ->getResult();
       // dump($bestAds);
       $worstAds=$manager->createQuery(
        'SELECT AVG(c.rating) as note, a.title, a.id, u.fistName, u.lastName, u.picture
         FROM App\Entity\Comment c
         JOIN c.ad a
         JOIN a.author u
         GROUP BY a
         ORDER BY note ASC'
    )->setMaxResults(5)
    ->getResult();
        return $this->render('admin/dashboard/index.html.twig', [
            'status'=>compact('users','ads','bookings','comments'),
            'bestAds'=>$bestAds,
            'worstAds'=>$worstAds
        ]);
    }
}
 