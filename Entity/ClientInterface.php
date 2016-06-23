<?php

namespace Trinity\Component\EntityCore\Entity;

/**
 * Interface ClientInterface
 */
interface ClientInterface
{
    /** @return string */
    public function getNotificationUri();


    /** @return  boolean */
    public function isNotified();


    /** @return string */
    public function getSecret();


    /** @return string */
    public function getName();


    /** @return int */
    public function getId();
}
