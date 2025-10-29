<?php

namespace App\View\Components\Card\Product;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Preview extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $description,
        public string $picture,
        public string $cost,
        public string $category,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card.product.preview');
    }
}
