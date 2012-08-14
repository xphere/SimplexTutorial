<?php

namespace Calendar\Event;

use Symfony\Component\HttpKernel\KernelEvents as Events;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LegalListener implements EventSubscriberInterface
{
    const LEGAL_HTML = '<a rel="license" title="This work is licensed under a Creative Commons Attribution-ShareAlike 3.0 Unported License" href="http://creativecommons.org/licenses/by-sa/3.0/deed.en_US"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/80x15.png" /></a>';

    public function onResponse(FilterResponseEvent $event)
    {
        if ($this->applicable($event)) {
            $response = $event->getResponse();
            $response->setContent($response->getContent() . self::LEGAL_HTML);
        }
    }

    protected function applicable(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if ($response->isRedirection()) {
            return false;
        }

        $headers = $response->headers;
        if ($headers->has('Content-Type') && strpos($headers->get('Content-Type'), '/html') === false) {
            return false;
        }

        return $event->getRequest()->getRequestFormat() === 'html';
    }

    public static function getSubscribedEvents()
    {
        return array(
            Events::RESPONSE => 'onResponse',
        );
    }
}
