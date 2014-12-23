<?php

namespace Woecifaun\Bundle\TranslationBridgeBundle\Model;

use Symfony\Component\HttpFoundation\Request;

use Woecifaun\Bundle\TranslationBridgeBundle\Configuration\TranslationBridge as Configuration;

/**
 * Translation Bridge interface
 */
interface TranslationBridgeInterface
{
    /**
     * Sets the request
     *
     * @param Request $request
     *
     * @return self
     */
    public function setRequest(Request $request);

    /**
     * Sets the configuration defined in the TranslationBridge Annotation
     *
     * @param Configuration $config
     *
     * @return self
     */
    public function setConfiguration(Configuration $config);

    /**
     * Gets an array of translated URLs for the current page
     *
     * @return array
     */
    public function generateBridge();
}
