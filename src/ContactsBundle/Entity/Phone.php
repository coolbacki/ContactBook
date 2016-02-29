<?php

namespace ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="phones")
 * @ORM\Entity(repositoryClass="ContactsBundle\Entity\PhoneRepository")
 */
class Phone
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
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     * @Assert\Length(min=9, max=9)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="numberDescription", type="string", length=50)
     */
    private $numberDescription;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="phones")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

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
     * Set number
     *
     * @param integer $number
     * @return Phone
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set numberDescription
     *
     * @param string $numberDescription
     * @return Phone
     */
    public function setNumberDescription($numberDescription)
    {
        $this->numberDescription = $numberDescription;

        return $this;
    }

    /**
     * Get numberDescription
     *
     * @return string 
     */
    public function getNumberDescription()
    {
        return $this->numberDescription;
    }

    /**
     * Set person
     *
     * @param \ContactsBundle\Entity\Person $person
     * @return Phone
     */
    public function setPerson(\ContactsBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \ContactsBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}
