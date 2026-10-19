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

    public $referenceProductCategoryId = "";

    public $new = 0;

    public $ver = 0;
    public function __construct($prodData = [], $productDetailsku = "", $referenceProductCategoryId = "", $referencedProductclass = "", $referencedProductevent, $new=0, $ver=0)
    {
        $this->prodData = $prodData;
        $this->productDetailsku = $productDetailsku;
        $this->referencedProductclass = $referencedProductclass;
        $this->referenceProductCategoryId = $referenceProductCategoryId;
        $this->referencedProductevent = $referencedProductevent;
        $this->new = $new;
        $this->ver = $ver;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if($this->ver == '2026')
            return view('components.productboxref2026');
        else
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
