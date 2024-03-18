<?php
namespace App\Event;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
#[AsEventListener(event: BaseExeEvent::class, method: 'onCheckEventMethod')]
final class BasicEventListner
{
    /**
     * Responsible For Listen Event
     * @return void
     */
    public function onCheckEventMethod(): void
    {
        dd("Event File with Event Listener");
    }
}