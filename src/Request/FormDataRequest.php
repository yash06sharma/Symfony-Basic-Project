<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class FormDataRequest
{
    /**
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @Assert\NotBlank
     * @Assert\Type("integer")
     */
    protected $age;

    /**
     * @Assert\NotBlank
     */
    protected $mobile;

    /**
     * @Assert\NotBlank
     */
    protected $course;

    /**
     * @Assert\NotBlank
     */
    protected $city;

    /**
     * @Assert\NotBlank
     */
    protected $image;

    // Getters and setters for each property if needed
}


?>