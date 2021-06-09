<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingsController extends AbstractController
{
    /**
     * @Route("/admin/bookings", name="admin_booking_index")
     */
    public function index(BookingRepository $repo): Response
    {
        return $this->render('admin/booking/index.html.twig', [
            
            'bookings' => $repo->findAll()
                    ]);
    }

    /**
     * 
     * Permet d'editer une reservation 
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * @param Booking $booking
     * @return Response
     */
    public function edit(Booking $booking, Request $request){

        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager = $this->getDoctrine()->getManager();
            $manager ->persist($booking);
            $manager ->flush();

            $this->addFlash(
                'success',
                "La reservation n° {$booking->getId()} a bien été modifiée"
            );
            return $this->redirectToRoute("admin_booking_index");
        }
        return $this->render('admin/booking/edit.html.twig',[
            'form' => $form->createView(),
            'booking' =>$booking 
        ]);

    }
    /**
     * permet de supprimer une reservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * 
     * @param Booking $booking
     * @return Response
     */
    public function delete(Booking $booking){
       
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($booking);
            $manager->flush();
       
        $this->addFlash(
            'success',
            "La reservation a bien été supprimée !"
        );
        
        
        return $this->redirectToRoute('admin_booking_index');
}
}
