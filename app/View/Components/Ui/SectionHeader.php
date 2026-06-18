<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class SectionHeader extends Component
{
    public string $title;

    public ?string $description;

    public function __construct(string $title, ?string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public function render()
    {
        return view('components.ui.section-header');
    }
}
