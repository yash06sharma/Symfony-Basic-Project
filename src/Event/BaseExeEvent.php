<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;
class BaseExeEvent extends Event
{
    public const NAME = 'base.created';
}

?>