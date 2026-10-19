<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	
    public function __construct(public string $btype='',public string $name='',public string $btntext='',public string $link='',public string $classname='',public string $btitle='',public array $bsvg = [],public string $bid='')
    {
        $this->btype = $btype;
		$this->name = $name;
		$this->btntext = $btntext;
		if(isset($link) && $link!='')	
			$this->link = $link;
		$this->classname = $classname;
		$this->bsvg = $bsvg;
		$this->btitle = $btitle;
        $this->bid = $bid;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}

