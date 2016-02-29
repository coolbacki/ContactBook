<?php

namespace ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="persons")
 * @ORM\Entity(repositoryClass="ContactsBundle\Entity\PersonRepository")
 */
class Person
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @Assert\false()
     *
     */

    public function isNameNotTheSameAsSurname(){
        return $this->name == $this->surname;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=50)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="person")
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="Email", mappedBy="person")
     */
    private $emails;

    /**
     * @ORM\ManyToMany( targetEntity = "ContactGroup",
     *     inversedBy="persons")
     * @ORM\JoinTable(name="persons_contactGroups",
     *     joinColumns={@ORM\JoinColumn(onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(onDelete="CASCADE")}
     * )
     */
    private $contactGroups;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Person
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Person
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Person
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->emails = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contactGroups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add addresses
     *
     * @param \ContactsBundle\Entity\Address $addresses
     * @return Person
     */
    public function addAddress(\ContactsBundle\Entity\Address $addresses)
    {
        $this->addresses[] = $addresses;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \ContactsBundle\Entity\Address $addresses
     */
    public function removeAddress(\ContactsBundle\Entity\Address $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phones
     *
     * @param \ContactsBundle\Entity\Phone $phones
     * @return Person
     */
    public function addPhone(\ContactsBundle\Entity\Phone $phones)
    {
        $this->phones[] = $phones;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \ContactsBundle\Entity\Phone $phones
     */
    public function removePhone(\ContactsBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add emails
     *
     * @param \ContactsBundle\Entity\Email $emails
     * @return Person
     */
    public function addEmail(\ContactsBundle\Entity\Email $emails)
    {
        $this->emails[] = $emails;

        return $this;
    }

    /**
     * Remove emails
     *
     * @param \ContactsBundle\Entity\Email $emails
     */
    public function removeEmail(\ContactsBundle\Entity\Email $emails)
    {
        $this->emails->removeElement($emails);
    }

    /**
     * Get emails
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add contactGroups
     *
     * @param \ContactsBundle\Entity\ContactGroup $contactGroups
     * @return Person
     */
    public function addContactGroup(\ContactsBundle\Entity\ContactGroup $contactGroups)
    {
        $this->contactGroups[] = $contactGroups;

        return $this;
    }

    /**
     * Remove contactGroups
     *
     * @param \ContactsBundle\Entity\ContactGroup $contactGroups
     */
    public function removeContactGroup(\ContactsBundle\Entity\ContactGroup $contactGroups)
    {
        $this->contactGroups->removeElement($contactGroups);
    }

    /**
     * Get contactGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContactGroups()
    {
        return $this->contactGroups;
    }
}
