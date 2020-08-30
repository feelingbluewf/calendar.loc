<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group_list;

class GroupPanel extends Component
{
    public function render()
    {
    	$parent_group_id = session()->get('parent_group_id', '');
    	
         return view('livewire.group-panel', [
            'groups' => Group_list::getGroupByParent(end($parent_group_id))
        ]);

    }
}
