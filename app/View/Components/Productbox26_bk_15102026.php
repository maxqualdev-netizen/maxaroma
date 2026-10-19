<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Productbox26 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $prodData = [];
	public $sliderClass = '';
	public $page = '';
    public $prodIndex = '';

	public function __construct($prodData=[],$sliderClass='',$page = '', $prodIndex = '')
    {
		$this->prodData = $prodData;
		$this->sliderClass = $sliderClass;
		$this->page = $page;
        $this->prodIndex = $prodIndex;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.productbox-2026');
    }
}
