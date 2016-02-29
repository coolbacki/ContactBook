<?php

namespace ContactsBundle\Controller;

use ContactsBundle\Entity\ContactGroup;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactGroupController extends Controller
{

    // funkcja wiąże Grupe z osoba
    private function mapContactGroupAndPerson(ContactGroup $contactGroup, $person)
    {
        $person->addContactGroup($contactGroup);
        $contactGroup->addPerson($person);
    }


    private function generateContactGroupForm(ContactGroup $contactGroup)
    {
        // Tworzymy formularz z obiektem na którym będziemy to robić
        $form = $this->createFormBuilder($contactGroup);

        // wypełnam inputy
        $form->add('groupName', 'text');
        $form->add('persons', 'entity', array('class' => 'ContactsBundle\Entity\Person', 'choice_label' => 'name',
            'expanded' => 'true', 'multiple' => 'true')); // dla wiele do wielu expanded + multiple = checkbox

        //create czy update
        if ($contactGroup->getId() === null) {
            $form->add('save', 'submit', ['label' => 'Dodaj grupę']);
            $form->setAction($this->generateUrl('newContactGroupSave'));

        } else {
            $form->add('save', 'submit', ['label' => 'zapisz edycje']);
            $form->setAction($this->generateUrl('modifyContactGroupSave', ['id' => $contactGroup->getId()]));
        }

        // zapisz formularz do obiektu
        $contactGroupForm = $form->getForm();

        return $contactGroupForm;
    }


    /**
     * @Route("/contactGroup/new",
     *     name="newContactGroupForm")
     * @Method("GET")
     * @Template()
     */
    public function newContactGroupAction()
    {
        // Tworzymy pusty obiekt
        $contactGroup = new ContactGroup();

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroups = $repository->findAll();

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $contactGroupForm = $this->generateContactGroupForm($contactGroup);

        //przekaż widok
        return ['form' => $contactGroupForm->createView(), 'contactGroups' => $contactGroups];
    }

    /**
     * @Route("/contactGroup/new",
     *     name="newContactGroupSave")
     * @Method("POST")
     * @Template()
     */
    public function newSaveContactGroupAction(Request $req)
    {
        // Tworzymy obiekt
        $contactGroup = new ContactGroup();

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateContactGroupForm($contactGroup);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            // mapuję grupę z osobą
            $persons = $contactGroup->getPersons();
            foreach ($persons as $person) {
                $this->mapContactGroupAndPerson($contactGroup, $person);
            }
            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($contactGroup);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('showAllContactGroup');
    }

    /**
     * @Route("/contactGroup/{id}/modify",
     *     name="modifyContactGroupForm")
     * @Method("GET")
     * @Template()
     */
    public function modifyContactGroupAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroup = $repository->find($id);

        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroups = $repository->findAll();

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $contactGroupForm = $this->generateContactGroupForm($contactGroup);

        //przekaż widok
        return ['form' => $contactGroupForm->createView(), 'contactGroups' => $contactGroups];
    }

    /**
     * @Route("/contactGroup/{id}/modify",
     *     name="modifyContactGroupSave")
     * @Method("POST")
     * @Template()
     */
    public function modifySaveAction(Request $req, $id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroup = $repository->find($id);

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generateContactGroupForm($contactGroup);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($contactGroup);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('showAllContactGroup');
    }

    /**
     * @Route("/contactGroup/{id}/delete",
     *     name="deleteContactGroup")
     * @Template("ContactsBundle:ContactGroup:show.html.twig")
     */
    public function deleteContactGroupAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroup = $repository->find($id);

        // pobieram entitymanager
        $em = $this->getDoctrine()->getManager();

        //usuwamy obiekt
        $em->remove($contactGroup);

        //Poinformuj EM zeby zapisac zmiany
        $em->flush();

        return $this->redirectToRoute('showAllContactGroup');
    }

    /**
     * @Route("/contactGroup/{id}",
     *     name="showContactGroup")
     * @Template()
     */
    public function showContactGroupAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroup = $repository->find($id);

        return ['contactGroup' => $contactGroup];
    }

    /**
     * @Route("/contactGroup/",
     *     name="showAllContactGroup")
     * @Template()
     */
    // W twigu renderuję listę do bloku lewego
    // służy to temu żeby móc wyświetlać listę grup podczas wyświetlania bloku prawego
    public function showAllContactGroupAction()
    {
        return [];
    }


    /**
     * @Route("/contact/group/list",)
     * @Template()
     */
    // wyświetlam listę bez bloków (można renderować w dowolne miejsce
    public function contactGroupListAction()
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:ContactGroup');
        $contactGroups = $repository->findAll();

        return ['contactGroups' => $contactGroups];
    }
}
