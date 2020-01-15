<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ChurroTimeEntryRepository")
 * @ORM\Table()
 */
class ChurroTimeEntry
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startCookingAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endCookingAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startCleanupAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $endCleanupAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityMade;

    /**
     * @var Baker
     *
     * @ORM\ManyToOne(targetEntity="Baker", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bakedBy;

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return DateTime
     */
    public function getStartCookingAt()
    {
        return $this->startCookingAt;
    }

    /**
     * @return void
     */
    public function setStartCookingAt(DateTime $startCookingAt)
    {
        $this->startCookingAt = $startCookingAt;
    }

    /**
     * @return DateTime
     */
    public function getEndCookingAt()
    {
        return $this->endCookingAt;
    }

    /**
     * @return void
     */
    public function setEndCookingAt(DateTime $endCookingAt)
    {
        $this->endCookingAt = $endCookingAt;
    }

    /**
     * @return DateTime
     */
    public function getStartCleanupAt()
    {
        return $this->startCleanupAt;
    }

    public function setStartCleanupAt(DateTime $startCleanupAt)
    {
        $this->startCleanupAt = $startCleanupAt;
    }

    /**
     * @return DateTime
     */
    public function getEndCleanupAt()
    {
        return $this->endCleanupAt;
    }

    /**
     * @param DateTime $endCleanupAt
     */
    public function setEndCleanupAt(DateTime $endCleanupAt)
    {
        $this->endCleanupAt = $endCleanupAt;
    }

    public function getQuantityMade()
    {
        return $this->quantityMade;
    }

    public function setQuantityMade($quantityMade)
    {
        $this->quantityMade = $quantityMade;
    }

    public function getBakedBy()
    {
        return $this->bakedBy;
    }

    public function setBakedBy($bakedBy)
    {
        $this->bakedBy = $bakedBy;
    }
}
