<?php

namespace ContactsBundle\Controller;

use ContactsBundle\Entity\Email;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmailController extends Controller
{
    private function generateEmailForm(Email $email, $personId)
    {
        // Tworzymy formularz z obiektem na którym będziemy to robić
        $form = $this->createFormBuilder($email);

        // wypełnam inputy
        $form->add('email', 'text');
        $form->add('emailDescription', 'text');

        //create czy update
        if ($email->getId() === null) {
            $form->add('save', 'submit', ['label' => 'Dodaj email']);
            $form->setAction($this->generateUrl('newEmailSave', ['personId' => $personId]));

        } else {
            $form->add('save', 'submit', ['label' => 'zapisz edycje']);
            $form->setAction($this->generateUrl('modifyEmailSave',
                ['id' => $email->getId(),'personId' => $personId]));
        }

        // zapisz formularz do obiektu
        $emailForm = $form->getForm();

        return $emailForm;
    }


    /**
     * @Route("/{personId}/email/new",
     *     name="newEmailForm")
     * @Method("GET")
     * @Template()
     */
    public function newEmailAction($personId)
    {
        // Tworzymy pusty obiekt
        $email = new Email();

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $emailForm = $this->generateEmailForm($email, $personId);

        //przekaż widok
        return ['form' => $emailForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/email/new",
     *     name="newEmailSave")
     * @Method("POST")
     * @Template()
     */
    public function newSaveEmailAction(Request $req, $personId)
    {
        // Tworzymy obiekt
        $email = new Email();

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateEmailForm($email, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);
        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted() && $form->isValid()) {


            $email->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($email);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        } else {
            return $this->render("ContactsBundle:Email:newEmail.html.twig", ['form' => $form->createView(), 'person' => $person]);
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/{personId}/email/{id}/modify",
     *     name="modifyEmailForm")
     * @Method("GET")
     * @Template()
     */
    public function modifyEmailAction($id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $email = $repository->find($id);

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $emailForm = $this->generateEmailForm($email, $personId);

        //przekaż widok
        return ['form' => $emailForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/email/{id}/modify",
     *     name="modifyEmailSave")
     * @Method("POST")
     * @Template()
     */
    public function modifySaveAction(Request $req, $id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $email = $repository->find($id);

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateEmailForm($email, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);

            $email->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($email);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/email/{id}/delete",
     *     name="deleteEmail")
     * @Template("ContactsBundle:Email:show.html.twig")
     */
    public function deleteEmailAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $email = $repository->find($id);

        $personId = $email->getPerson()->getId();

        // pobieram entitymanager
        $em = $this->getDoctrine()->getManager();

        //usuwamy obiekt
        $em->remove($email);

        //Poinformuj EM zeby zapisac zmiany
        $em->flush();

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/email/{id}",
     *     name="showEmail")
     * @Template()
     */
    public function showEmailAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $email = $repository->find($id);

        return ['email' => $email];
    }

    /**
     * @Route("/email/")
     * @Template()
     */
    public function showAllEmailAction()
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Email');
        $emails = $repository->findAll();

        return ['emails' => $emails];
    }
}
