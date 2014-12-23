<?php

namespace Woecifaun\Bundle\TranslationBridgeBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * TranslationBridge Annotation
 * 
 * @Annotation
 */
class TranslationBridge extends ConfigurationAnnotation
{
    /**
     * Corresponding list of wildcards with controller parameters properties
     *
     * @var array
     */
    private $placeholders = [];

    /**
     * Returns the annotation alias name.
     *
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'translation_bridge';
    }

    /**
     * Only one template directive is allowed
     *
     * @return bool
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return false;
    }

    /**
     * Gets the value of placeholders.
     *
     * @return array
     */
    public function getPlaceholders()
    {
        return $this->placeholders;
    }

    /**
     * Sets the value of placeholders.
     *
     * @param array $placeholders the placeholders
     *
     * @return self
     */
    public function setPlaceholders(array $placeholders)
    {
        $this->placeholders = $placeholders;

        return $this;
    }
}
