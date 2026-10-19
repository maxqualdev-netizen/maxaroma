<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Filter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $filterData = [];
	public $filterAttr = [];
	public $filterSelected = [];
    public $test = 0;

	public function __construct($filterData=[],$filterAttr=[],$filterSelected=[],$test = 0)
    {
		$this->filterData = $filterData;
		$this->filterAttr = $filterAttr;
		$this->filterSelected = $filterSelected;
        $this->test = $test;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if($this->test == '1'){
            return view('components.filter_new');
        } else {
            return view('components.filter');
        }
        //return view('components.filter_new');
    }
}
