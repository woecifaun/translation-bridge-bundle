<?php

namespace Woecifaun\Bundle\TranslationBridgeBundle\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

use Woecifaun\Bundle\TranslationBridgeBundle\Configuration\TranslationBridge as Configuration;
use Woecifaun\Bundle\TranslationBridgeBundle\Exception\InvalidPropertiesException;
use Woecifaun\Bundle\TranslationBridgeBundle\Exception\ParamNotFoundException;

/**
 * The TranslationBridgeListener class handles the TranslationBridge annotation.
 */
class TranslationBridge implements TranslationBridgeInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * locales for which translation links must be generated
     *
     * @var array
     */
    private $appLocales;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * Construct
     *
     * @param RouterInterface           $router
     * @param PropertyAccessorInterface $accessor
     * @param array                     $appLocales
     */
    public function __construct(
        RouterInterface $router,
        PropertyAccessorInterface $accessor,
        $appLocales
    ) {
        $this->router     = $router;
        $this->accessor   = $accessor;
        $this->appLocales = $appLocales;
    }

    /**
     * {@inheritdoc }
     */
    public function setRequest(Request $request)
    {ld($request);die;
        $this->request = $request;

        return $this;
    }

    /**
     * {@inheritdoc }
     */
    public function setConfiguration(Configuration $config)
    {
        $this->config = $config;

        return $this;
    }

    private function getParameter($property, $locale)
    {
        list($paramName, $property) = explode('.', $property, 2);

        if (!$param = $this->request->get($paramName)) {
            throw new ParamNotFoundException($paramName, $this->request->get('_route'));
        }

        if (method_exists($param, 'setCurrentLocale')) {
            $param->setCurrentLocale($locale);
        }

        return $this->accessor->getValue($param, $property);
    }

    /**
     * {@inheritdoc }
     */
    public function generateBridge()
    {
        $bridge = [];
        $route  = $this->request->get('_route');

        foreach ($this->appLocales as $locale) {
            $parameters = ['locale' => $locale];

            foreach ($this->config->getPlaceholders() as $wildcard => $value) {
                $parameters[$wildcard] = $this->getParameter($value, $locale);
            }

            $bridge[$locale] = $this->router->generate($route, $parameters, true);
        }

        return $bridge;
    }
}
