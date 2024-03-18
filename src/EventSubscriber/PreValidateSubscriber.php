<?php 
namespace App\EventSubscriber;


use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Campain;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;



class PreValidateSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // KernelEvents::VIEW
        return [
            KernelEvents::VIEW => ['preValidate', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function preValidate(ViewEvent $event):void
    {
        $object = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$object instanceof Campain || Request::METHOD_POST !== $method) {
            return;
        }
        $object->setFirstname('Raju');
        
    }
}