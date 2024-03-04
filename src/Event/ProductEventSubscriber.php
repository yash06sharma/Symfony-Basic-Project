<?php
namespace App\Event;
use App\Event\ProductCreateEvent;
use App\Event\ProductUpdateEvent;
use App\Event\ProductDeleteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
class ProductEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return[
            ProductCreateEvent::NAME => 'onProductCreation',
            ProductUpdateEvent::NAME => [
                'onProductUpdation', 
            ],
            ProductDeleteEvent::NAME => 'onProductDeletion',
            // KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }   

    public function onProductCreation(ProductCreateEvent $event)
    {
        dd("Created Event Subscriber");
    }
    public function onProductUpdation(ProductUpdateEvent $event)
    {
        dd("Updated Event Subscriber");
    }
    public function onProductDeletion(ProductDeleteEvent $event)
    {
        dd("Deleted Event Subscriber");
    }
    // public function onKernelResponse(ResponseEvent  $event)
    // {
    //     dd("Kernal Event Subscriber");
    // }


}


?>