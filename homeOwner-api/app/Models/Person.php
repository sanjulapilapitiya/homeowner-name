<?php

namespace App\Models;

class Person
{
    public string $title;
    public ?string $first_name;
    public ?string $initial;
    public string $last_name;

    // Constructor to initialize the properties
    public function __construct($title, $first_name, $initial, $last_name)
    {
        $this->title = $title;
        $this->first_name = $first_name;
        $this->initial = $initial;
        $this->last_name = $last_name;
    }
}