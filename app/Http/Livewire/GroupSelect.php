<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Group;

class GroupSelect extends Component
{

	public $select = ''; 
	
    public function render()
    {

    	$group_id = session()->get('group_id', '');

    	 return view('livewire.group-select', [
    	 	'group_id' => Group::select($this->select),
            'groups' => Group::index()
        ]);
    }
}
