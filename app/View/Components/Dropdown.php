<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Dropdown extends Component
{
    public $selectName;
    public $options;
    public $selected;

    public function __construct($selectName, $options = [], $selected = null)
    {
        $this->selectName = $selectName;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function render(): View|Closure|string
    {
        return view('components.dropdown');
    }
}