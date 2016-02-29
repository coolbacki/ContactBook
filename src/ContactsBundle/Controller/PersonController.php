<?php

namespace ContactsBundle\Controller;

use ContactsBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonController extends Controller
{
    private function generatePersonForm(Person $person)
    {
        // Tworzymy formularz z obiektem na którym będziemy to robić
        $form = $this->createFormBuilder($person);

        // wypełnam inputy
        $form->add('name', 'text');
        $form->add('surname', 'text');
        $form->add('description', 'text');
        $form->add('contactGroups', 'entity', array('class' => 'ContactsBundle\Entity\ContactGroup',
            'choice_label' => 'groupName',
            'expanded' => 'true', 'multiple' => 'true')); // dla wiele do wielu expanded + multiple = checkbox


        //create czy update
        if ($person->getId() === null) {
            $form->add('save', 'submit', ['label' => 'Dodaj Osobe']);
            $form->setAction($this->generateUrl('newSave'));

        } else {
            $form->add('save', 'submit', ['label' => 'zapisz edycje']);
            $form->setAction($this->generateUrl('modifySave', ['id' => $person->getId()]));
        }

        // zapisz formularz do obiektu
        $personForm = $form->getForm();

        return $personForm;
    }


    /**
     * @Route("/new",
     *     name="newForm")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        // Tworzymy pusty obiekt
        $person = new Person();

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $personForm = $this->generatePersonForm($person);

        //przekaż widok
        return ['form' => $personForm->createView()];
    }

    /**
     * @Route("/new",
     *     name="newSave")
     * @Method("POST")
     * @Template()
     */
    public function newSaveAction(Request $req)
    {
        // Tworzymy obiekt
        $person = new Person();

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generatePersonForm($person);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($person);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        //pobieram id
        $noweId = $person->getId();

        return $this->redirectToRoute('show', ['id' => $noweId]);
    }

    /**
     * @Route("/{id}/modify",
     *     name="modifyForm")
     * @Method("GET")
     * @Template()
     */
    public function modifyAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($id);

        // wywołujemy pomocniczą funkcję (tworzącą formularz) na nowym obiekcie
        $personForm = $this->generatePersonForm($person);

        //przekaż widok
        return ['form' => $personForm->createView()];
    }

    /**
     * @Route("/{id}/modify",
     *     name="modifySave")
     * @Method("POST")
     * @Template()
     */
    public function modifySaveAction(Request $req, $id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($id);

        // Pobieramy pusty formularz (z funkcji pomocniczej)
        $form = $this->generatePersonForm($person);

        // Wypełniamy pusty formularz requestem
        $form->handleRequest($req);

        // Sprawdzamy czy formularz został przysłany
        if ($form->isSubmitted()) {

            // pobieram entitymanager
            $em = $this->getDoctrine()->getManager();

            //zapiemnamy obiekt
            $em->persist($person);

            //Poinformuj EM zeby zapisac zmiany
            $em->flush();
        }

        return $this->redirectToRoute('show', ['id' => $id]);
    }

    /**
     * @Route("/{id}/delete",
     *     name="delete")
     * @Template("ContactsBundle:Person:show.html.twig")
     */
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($id);

        // pobieram entitymanager
        $em = $this->getDoctrine()->getManager();

        //usuwamy obiekt
        $em->remove($person);

        //Poinformuj EM zeby zapisac zmiany
        $em->flush();

        return $this->redirectToRoute('showAll');
    }

    /**
     * @Route("/{id}",
     *     name="show")
     * @Template()
     */
    public function showAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $person = $repository->find($id);

        return ['person' => $person];
    }


    /**
     * @Route("/showPerson",
     *     name="showPerson")
     * @Template()
     */
    // wyświetlam widok osoby do includowania w blokach (lista, new, modify) - bloki muszą zwracać 'person'
    public function showPersonAction()
    {
        return [];
    }



    /**
     * @Route("/",
     *     name="showAll")
     * @Method("GET")
     * @Template()
     */
    // W twigu renderuję listę do bloku lewego
    // służy to temu żeby móc wyświetlać listę grup podczas wyświetlania bloku prawego
    public function showAllAction()
    {
        return [];
    }

    /**
     * @Route("/",
     *     name="searchPerson")
     * @Method("POST")
     * @Template()
     */
    public function searchPersonAction(Request $req)
    {
        //wyciągam wyszukiwaną wartość z posta
        $val = $req->request->get("search");

        //wyciągam listę osób pasujących do wyszukanej wartości
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $persons = $repository->searchBy($val);

        return ['persons' => $persons];
    }

    /**
     * @Route("/person/list",)
     * @Template()
     */

    // wyświetlam listę osób bez bloków - możliwość renderu gdziekolwiek
    public function personListAction()
    {
        $repository = $this->getDoctrine()->getRepository('ContactsBundle:Person');
        $persons = $repository->showOrderedByName();

        return ['persons' => $persons];
    }

}
