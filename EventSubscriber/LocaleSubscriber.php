<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    // Language by default
    private $defaultLocale;

    public function __construct($defaultLocale = 'en') {
        $this->defaultLocale = $defaultLocale;
    }

    public static function getSubscribedEvents() {
        // Set a high priority
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }

    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        // Check if the language is passed as a parameter of the URL
        if ($locale = $request->query->get('_locale')) {
            $request->setLocale($locale);
        }
        // Otherwise we use that of the session
        else {
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }

    }


}

