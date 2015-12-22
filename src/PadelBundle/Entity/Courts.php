<?php

namespace PadelBundle\Entity;

/**
 * Courts
 */
class Courts
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $active;

     /**
     * Constructor
     * @param boolean $active
     */
     public function __construct($active=true)
     {
        $this->active = $active;
     }

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
     * Set active
     *
     * @param boolean $active
     *
     * @return Courts
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
    
    public function __toString() {
        return (string)$this->id;
    }
}

