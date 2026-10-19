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
    public $type = "";
    public $sliderLink = "";
    public $ss = "";

	public function __construct($attr=[],$sliderData=[],$ver='',$ss='',$type='',$sliderLink='')
    {
		$this->attr = $attr;
		$this->sliderData = $sliderData;
        $this->ver = $ver;
        $this->type = $type;
        $this->sliderLink = $sliderLink;
        $this->ss = $ss;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        //$this->ver = 1;
        if($this->ver != 2026)
            $this->ver = 1;

        if($this->ss == 1)
            return view('components.slider-ss');
        elseif($this->ver == 1)
            return view('components.slider-new');
        elseif($this->ver == 2026)
            return view('components.slider-2026');
        else
            return view('components.slider');
    }
}
