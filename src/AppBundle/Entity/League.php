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
 * @ORM\Table(name="leagues")
 * @ORM\HasLifecycleCallbacks
 * @GEDMO\SoftDeleteable(fieldName="deletedAt")
 * @ExclusionPolicy("all")
 */
class League extends BaseEntity {

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length = 50)
     * @Expose
     * @Groups({"League", "Team"})
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Team")
     * @ORM\JoinTable(name="league_team",
     *      joinColumns={@ORM\JoinColumn(name="league_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id")}
     *  )
     * @var Collection
     * @Expose
     * @Groups("League")
     */
    protected $teams;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected $starterPlayers;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected $substitutePlayers;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     */
    protected $salaryCap;

    function __construct() {
        $this->teams = new ArrayCollection();
    }

    function getName() {
        return $this->name;
    }

    function getTeams() {
        return $this->teams;
    }

    function getStarterPlayers() {
        return $this->starterPlayers;
    }

    function getSubstitutePlayers() {
        return $this->substitutePlayers;
    }

    function getSalaryCap() {
        return $this->salaryCap;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function setTeams(Collection $teams) {
        $this->teams = $teams;
        return $this;
    }

    function setStarterPlayers($starterPlayers) {
        $this->starterPlayers = $starterPlayers;
        return $this;
    }

    function setSubstitutePlayers($substitutePlayers) {
        $this->substitutePlayers = $substitutePlayers;
        return $this;
    }

    function setSalaryCap($salaryCap) {
        $this->salaryCap = $salaryCap;
        return $this;
    }

}
