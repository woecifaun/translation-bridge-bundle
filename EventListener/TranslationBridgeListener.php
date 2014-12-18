<?php

namespace Woecifaun\Bundle\TranslationBridgeBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\RouterInterface;

/**
 * The TemplateListener class handles the Template annotation.
 */
class TranslationBridgeListener implements EventSubscriberInterface
{
    private $appLocales;

    private $router;

    /**
     * Construct
     *
     * @param RouterInterface $router
     * @param array           $appLocales
     */
    public function __construct(RouterInterface $router, $appLocales)
    {
        $this->router     = $router;
        $this->appLocales = $appLocales;
    }

    /**
     * Guesses the template name to render and its variables and adds them to
     * the request object.
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

        $bridge =  [];
        $route = $request->get('_route');
        foreach ($this->appLocales as $locale) {
            $bridge[$locale] = $this->router->generate($route, ['locale' => $locale], true);
        }

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

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array('onKernelController'),
            KernelEvents::VIEW => array('onKernelView', 1),
        );
    }
}
