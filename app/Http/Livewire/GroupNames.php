<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Group;

class GroupNames extends Component
{

	public $select = ''; 
	
    public function render()
    {
    	 return view('livewire.group-names', [
    	 	'group_id' => Group::select($this->select),
            'groups' => Group::index()
        ]);
    }
}
