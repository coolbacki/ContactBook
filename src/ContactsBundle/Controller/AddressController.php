<?php

namespace ContactsBundle\Controller;

use ContactsBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    private function generateAddressForm(Address $address, $personId)
    {
        // Tworzymy formularz z obiektem na którym będziemy to robić
        $form = $this->createFormBuilder($address);

        // wypełnam inputy
        $form->add('city', 'text');
        $form->add('street', 'text');
        $form->add('building', 'number');
        $form->add('flat', 'number', ['required' => false]);

        //create czy update
        if ($address->getId() === null) {
            $form->add('save', 'submit', ['label' => 'Dodaj Adres']);
            $form->setAction($this->generateUrl('newAddressSave', ['personId' => $personId]));

        } else {
            $form->add('save', 'submit', ['label' => 'zapisz edycje']);
            $form->setAction($this->generateUrl('modifyAddressSave',
                ['id' => $address->getId(),'personId' => $personId]));
        }

        // zapisz formularz do obiektu
        $addressForm = $form->getForm();

        return $addressForm;
    }


    /**
     * @Route("/{personId}/address/new",
     *     name="newAddressForm")
     * @Method("GET")
     * @Template()
     */
    public function newAddressAction($personId)
    {
        // Tworzymy pusty obiekt
        $address = new Address();

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $addressForm = $this->generateAddressForm($address, $personId);

        //przekaż widok
        return ['form' => $addressForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/address/new",
     *     name="newAddressSave")
     * @Method("POST")
     * @Template()
     */
    public function newSaveAddressAction(Request $req, $personId)
    {
        // Tworzymy obiekt
        $address = new Address();

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateAddressForm($address, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);

            $address->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($address);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/{personId}/address/{id}/modify",
     *     name="modifyAddressForm")
     * @Method("GET")
     * @Template()
     */
    public function modifyAddressAction($id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $address = $repository->find($id);

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($personId);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $addressForm = $this->generateAddressForm($address, $personId);

        //przekaż widok
        return ['form' => $addressForm->createView(), 'person' => $person];
    }

    /**
     * @Route("/{personId}/address/{id}/modify",
     *     name="modifyAddressSave")
     * @Method("POST")
     * @Template()
     */
    public function modifySaveAction(Request $req, $id, $personId)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $address = $repository->find($id);

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateAddressForm($address, $personId);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
            $person = $repository->find($personId);

            $address->setPerson($person);

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($address);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/address/{id}/delete",
     *     name="deleteAddress")
     * @Template("ContactsBundle:Address:show.html.twig")
     */
    public function deleteAddressAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $address = $repository->find($id);

        $personId = $address->getPerson()->getId();

        // pobieram entitymanager
        $em = $this->getDoctrine()->getManager();

        //usuwamy obiekt
        $em->remove($address);

        //Poinformuj EM zeby zapisac zmiany
        $em->flush();

        return $this->redirectToRoute('show', ['id' => $personId]);
    }

    /**
     * @Route("/address/{id}",
     *     name="showAddress")
     * @Template()
     */
    public function showAddressAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $address = $repository->find($id);

        return ['address' => $address];
    }

    /**
     * @Route("/address/")
     * @Template()
     */
    public function showAllAddressAction()
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Address');
        $addresses = $repository->findAll();

        return ['addresses' => $addresses];
    }
}
