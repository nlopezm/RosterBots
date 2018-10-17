<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as GEDMO;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="players")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer") 
 * @ORM\DiscriminatorMap({1 = "Starter", 0 = "Substitute"})
 * @ORM\HasLifecycleCallbacks
 * @GEDMO\SoftDeleteable(fieldName="deletedAt")
 * @ExclusionPolicy("all")
 */
abstract class Player extends BaseEntity {

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length = 50)
     * @Expose
     * @Groups({"Team", "Player"})
     */
    protected $firstName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length = 50)
     * @Expose
     * @Groups({"Team", "Player"})
     */
    protected $lastName;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     * @Groups({"Player"})
     */
    protected $speed;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     * @Groups({"Player"})
     */
    protected $strength;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     * @Groups({"Player"})
     */
    protected $agility;

    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
     */
    protected $team;

    function __construct($firstName, $lastName, $speed, $strength, $agility) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->speed = $speed;
        $this->strength = $strength;
        $this->agility = $agility;
    }

    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getSpeed() {
        return $this->speed;
    }

    function getStrength() {
        return $this->strength;
    }

    function getAgility() {
        return $this->agility;
    }

    function getTeam() {
        return $this->team;
    }

    function setFirstName($firstName) {
        $this->firstName = $firstName;
        return $this;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
        return $this;
    }

    function setSpeed($speed) {
        $this->speed = $speed;
        return $this;
    }

    function setStrength($strength) {
        $this->strength = $strength;
        return $this;
    }

    function setAgility($agility) {
        $this->agility = $agility;
        return $this;
    }

    function setTeam($team) {
        $this->team = $team;
        return $this;
    }

    function getTotalAttributeScore() {
        return $this->agility + $this->speed + $this->strength;
    }

}
