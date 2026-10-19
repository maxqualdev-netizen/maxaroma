<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Productboxref extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $prodData = [];

    public $productDetailsku = '';

    public $referencedProductclass = '';

    public $referencedProductevent= '';
    
    public $new = 0;

    public function __construct($prodData = [], $productDetailsku = "", $referencedProductclass = "", $referencedProductevent, $new=0)
    {
        $this->prodData = $prodData;
        $this->productDetailsku = $productDetailsku;
        $this->referencedProductclass = $referencedProductclass;
        $this->referencedProductevent = $referencedProductevent;
        $this->new = $new;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.productboxref');
        /*
        if($this->new == 1)
        {
            return view('components.productboxref_new');
        } else {
            return view('components.productboxref');    
        }*/
    }
}
