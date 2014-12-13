<?php

namespace Woecifaun\TranslationBridgeBundle\Configuration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class TranslationBridge extends ConfigurationAnnotation
{
    public function setValue($value='')
    {
        $this->value = $value;
    }

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
}
