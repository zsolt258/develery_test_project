<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contact", name="contact.")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact")
     * @param $request
     */
    public function index(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $contact = $form->getData();

            if(empty($contact->getName()) || empty($contact->getEmail()) || empty($contact->getMessage()))
            {
                $this->addFlash('danger', 'Hiba! Kérjük töltsd ki az összes mezőt!');
            }
            else if(strlen($contact->getName()) > 50 || strlen($contact->getEmail()) > 50 || strlen($contact->getMessage()) > 1000)
            {
                $this->addFlash('danger', 'Hiba! A név és az e-mail cím hossza maximum 50 karakter lehet, míg az üzenet hossza maximum 1000.');
            }
            else
            {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();

                $this->addFlash('success', 'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.');
            }
        }

        return $this->render('contact/index.html.twig', [
            'controller_name'   =>      'ContactController',
            'form'              =>      $form->createView(),
        ]);
    }

}
