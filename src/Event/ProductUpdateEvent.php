<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;
class ProductUpdateEvent extends Event
{
    public const NAME = 'product.updated';
    // ....
}

?>