<?php

namespace Trinity\Component\EntityCore\Entity;

/**
 * Interface EntityInterface
 */
interface EntityInterface
{
    /**
     * Get id.
     */
    public function getId();


    /**
     * @return string
     */
    public function __toString();
}
