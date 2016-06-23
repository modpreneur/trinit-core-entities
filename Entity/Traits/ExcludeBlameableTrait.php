<?php

namespace Trinity\Component\EntityCore\Traits;

use JMS\Serializer\Annotation\PreSerialize;

/**
 * Class ExcludeBlameableTrait
 * @package Trinity\Component\EntityCore\Traits
 */
trait ExcludeBlameableTrait
{
    /**
     * @PreSerialize
     */
    public function preSerialize()
    {
        $this->setUpdatedBy(null);
        $this->setCreatedBy(null);
    }
}
