<?php

namespace Woecifaun\TranslationBridgeBundle\Tests\Configuration;

use Woecifaun\Bundle\TranslationBridgeBundle\Configuration\TranslationBridge;

class TranslationBridgeTest extends \PHPUnit_Framework_TestCase
{
    private $annotation;

    public function setUp()
    {
        $this->annotation = new TranslationBridge([]);
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testPublicMethods()
    {
        $this->assertEquals('translation_bridge', $this->annotation->getAliasName());

        $this->assertEquals(false, $this->annotation->allowArray());

        $array = ["entity.property" => "value"];
        $this->assertEquals(
            $this->annotation,
            $this->annotation->setPlaceholders($array)
        );
        $this->assertEquals($array, $this->annotation->getPlaceholders());

        $this->annotation->setPlaceholders('not an array');
    }
}
