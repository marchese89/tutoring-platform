<?php
// app/View/Components/Ui/Card.php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Card extends Component
{
    public string $class;

    public function __construct(string $class = '')
    {
        $this->class = $class;
    }

    public function render()
    {
        return view('components.ui.card');
    }
}
