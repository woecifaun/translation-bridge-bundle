<?php

namespace Woecifaun\Bundle\TranslationBridgeBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Woecifaun\Bundle\TranslationBridgeBundle\Model\TranslationBridgeInterface;

/**
 * The TranslationBridgeListener class handles the TranslationBridge annotation.
 */
class TranslationBridgeListener implements EventSubscriberInterface
{
    /**
     * The service generating the translated URLs
     *
     * @var array
     */
    private $translationBridge;

    /**
     * Construct
     *
     * @param TranslationBridgeInterface $translationBridge
     */
    public function __construct(TranslationBridgeInterface $translationBridge)
    {
        $this->translationBridge = $translationBridge;
    }

    /**
     * Generate the array of same page/different locale
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!is_array($controller = $event->getController())) {
            return;
        }

        $request = $event->getRequest();

        if (!$configuration = $request->attributes->get('_translation_bridge')) {
            return;
        }

        $bridge = $this->translationBridge
            ->setRequest($request)
            ->setConfiguration($configuration)
            ->generateBridge();
        ;

        $request->attributes->set('_translation_bridge', $bridge);
    }

    /**
     * Renders the template and initializes a new response object with the
     * rendered template content.
     *
     * @param GetResponseForControllerResultEvent $event A GetResponseForControllerResultEvent instance
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        $parameters = $event->getControllerResult();

        if (!$bridge = $request->attributes->get('_translation_bridge')) {
            return;
        }

        if (!is_array($parameters)) {
            return $parameters;
        }

        if (null === $parameters) {
            $parameters = [];
        }

        $parameters['translation_bridge'] = $bridge;

        $event->setControllerResult($parameters);
    }

    /**
     * Return an array of subscribed events
     *
     * @return array subscribed events
     */
    public static function getSubscribedEvents()
    {
        return array(
            // The onKernelController event must be listened
            // after any ParamConverter handling
            KernelEvents::CONTROLLER => array('onKernelController', -64),
            KernelEvents::VIEW       => array('onKernelView', 1),
        );
    }
}
