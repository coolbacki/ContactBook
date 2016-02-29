<?php

namespace ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="addresses")
 * @ORM\Entity(repositoryClass="ContactsBundle\Entity\AddressRepository")
 */
class Address
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
     * @ORM\Column(name="city", type="string", length=50)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50)
     */
    private $street;

    /**
     * @var integer
     *
     * @ORM\Column(name="building", type="integer")
     */
    private $building;

    /**
     * @var integer
     *
     * @ORM\Column(name="flat", type="integer")
     */
    private $flat;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="addresses")
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
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set building
     *
     * @param integer $building
     * @return Address
     */
    public function setBuilding($building)
    {
        $this->building = $building;

        return $this;
    }

    /**
     * Get building
     *
     * @return integer 
     */
    public function getBuilding()
    {
        return $this->building;
    }

    /**
     * Set flat
     *
     * @param integer $flat
     * @return Address
     */
    public function setFlat($flat)
    {
        $this->flat = $flat;

        return $this;
    }

    /**
     * Get flat
     *
     * @return integer 
     */
    public function getFlat()
    {
        return $this->flat;
    }

    /**
     * Set person
     *
     * @param \ContactsBundle\Entity\Person $person
     * @return Address
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
