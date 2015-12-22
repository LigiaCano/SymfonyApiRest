<?php

namespace PadelBundle\Entity;

/**
 * Reservations
 */
class Reservations
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var \PadelBundle\Entity\Courts
     */
    private $court;

    /**
     * @var \PadelBundle\Entity\Users
     */
    private $user;


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
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Reservations
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set court
     *
     * @param \PadelBundle\Entity\Courts $court
     *
     * @return Reservations
     */
    public function setCourt(\PadelBundle\Entity\Courts $court = null)
    {
        $this->court = $court;

        return $this;
    }

    /**
     * Get court
     *
     * @return \PadelBundle\Entity\Courts
     */
    private function getCourt()
    {
        return $this->court;
    }

    /**
     * Set user
     *
     * @param \PadelBundle\Entity\Users $user
     *
     * @return Reservations
     */
    public function setUser(\PadelBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PadelBundle\Entity\Users
     */
    private function getUser()
    {
        return $this->user;
    }
}

