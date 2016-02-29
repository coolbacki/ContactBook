<?php

namespace ContactsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="emails")
 * @ORM\Entity(repositoryClass="ContactsBundle\Entity\EmailRepository")
 */
class Email
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
     * @ORM\Column(name="email", type="string", length=140)
     * @Assert\NotBlank( message = "Nie podałeś adresu email" )
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="emailDescription", type="string", length=50)
     * @Assert\NotBlank( message = "Nie podałeś opisu adresu email" )
     */
    private $emailDescription;

    /**
     * @ORM\ManyToOne(targetEntity="Person", inversedBy="emails")
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
     * Set email
     *
     * @param string $email
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailDescription
     *
     * @param string $emailDescription
     * @return Email
     */
    public function setEmailDescription($emailDescription)
    {
        $this->emailDescription = $emailDescription;

        return $this;
    }

    /**
     * Get emailDescription
     *
     * @return string 
     */
    public function getEmailDescription()
    {
        return $this->emailDescription;
    }

    /**
     * Set person
     *
     * @param \ContactsBundle\Entity\Person $person
     * @return Email
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
