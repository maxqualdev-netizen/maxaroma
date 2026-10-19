<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Slider extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $attr = [];
	public $sliderData = [];
    public $ver = '';
    
	public function __construct($attr=[],$sliderData=[],$ver='')
    {
		$this->attr = $attr;
		$this->sliderData = $sliderData;
        $this->ver = $ver;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if($this->ver == 1)
            return view('components.slider-new');
        else
            return view('components.slider');
    }
}
