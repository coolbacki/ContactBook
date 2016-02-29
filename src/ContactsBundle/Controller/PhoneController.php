<?php

namespace ContactsBundle\Controller;

use ContactsBundle\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneController extends Controller
{
    private function generatePhoneForm(Phone $phone, $personId)
    {
        // Tworzymy formularz z obiektem na którym będziemy to robić
        $form = $this->createFormBuilder($phone);

        // wypełnam inputy
        $form->add('number', 'text');
        $form->add('numberDescription', 'text');

        //create czy update
        if ($phone->getId() === null) {
            $form->add('save', 'submit', ['label' => 'Dodaj Telefon']);
            $form->setAction($this->generateUrl('newPhoneSave', ['personId' => $personId]));

        } else {
            $form->add('save', 'submit', ['label' => 'zapisz edycje']);
            $form->setAction($this->generateUrl('modifyPhoneSave',
                ['id' => $phone->getId(),'personId' => $personId]));
        }

        // zapisz formularz do obiektu
        $phoneForm = $form->getForm();

        return $phoneForm;
    }


    /**
     * @Route("/{personId}/phone/new",
     *     name="newPhoneForm")
     * @Method("GET")
     * @Template()
     */
    public function newPhoneAction($personId)
    {
        // Tworzymy pusty obiekt
        $phone = new Phone();




        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $phoneForm = $this->generatePhoneForm($phone, $personId);

        //przekaż widok
        return ['form' => $phoneForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/phone/new",
     *     name="newPhoneSave")
     * @Method("POST")
     * @Template()
     */
    public function newSavePhoneAction(Request $req, $personId)
    {
        // Tworzymy obiekt
        $phone = new Phone();

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generatePhoneForm($phone, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);

            $phone->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($phone);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/{personId}/phone/{id}/modify",
     *     name="modifyPhoneForm")
     * @Method("GET")
     * @Template()
     */
    public function modifyPhoneAction($id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phone = $repository->find($id);

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $phoneForm = $this->generatePhoneForm($phone, $personId);

        //przekaż widok
        return ['form' => $phoneForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/phone/{id}/modify",
     *     name="modifyPhoneSave")
     * @Method("POST")
     * @Template()
     */
    public function modifySaveAction(Request $req, $id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phone = $repository->find($id);

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generatePhoneForm($phone, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);

            $phone->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($phone);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/phone/{id}/delete",
     *     name="deletePhone")
     * @Template("ContactsBundle:Phone:show.html.twig")
     */
    public function deletePhoneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phone = $repository->find($id);

        $personId = $phone->getPerson()->getId();

        // pobieram entitymanager
        $em = $this->getDoctrine()->getManager();

        //usuwamy obiekt
        $em->remove($phone);

        //Poinformuj EM zeby zapisac zmiany
        $em->flush();

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/phone/{id}",
     *     name="showPhone")
     * @Template()
     */
    public function showPhoneAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phone = $repository->find($id);

        return ['phone' => $phone];
    }

    /**
     * @Route("/phone/")
     * @Template()
     */
    public function showAllPhoneAction()
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Phone');
        $phones = $repository->findAll();

        return ['phones' => $phones];
    }
}
