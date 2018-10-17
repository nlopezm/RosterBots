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
 * @ORM\Table(name="teams")
 * @ORM\HasLifecycleCallbacks
 * @GEDMO\SoftDeleteable(fieldName="deletedAt")
 * @ExclusionPolicy("all")
 */
class Team extends BaseEntity {

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length = 50)
     * @Expose
     * @Groups({"League", "Team"})
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="League", inversedBy="teams")
     */
    protected $league;

    /**
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team", cascade={"persist"})
     * @Expose
     * @Groups({"Team"})
     */
    protected $players;

    function __construct() {
        $this->players = new ArrayCollection();
    }

    function getName() {
        return $this->name;
    }

    function getLeague() {
        return $this->league;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function setLeague($league) {
        $this->league = $league;
        return $this;
    }

    function setPlayers($players) {
        foreach ($players as $player)
            $player->setTeam($this);
        $this->players = $players;
        return $this;
    }

}
