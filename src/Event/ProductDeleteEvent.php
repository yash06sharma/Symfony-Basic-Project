<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;
class ProductDeleteEvent extends Event
{
    public const NAME = 'product.deleted';
    // ....
}

?>