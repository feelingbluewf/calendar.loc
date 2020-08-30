<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Group;
use App\Models\Group_list;
use App\Models\Post;

class Switching extends Component
{

	use WithPagination;
    public $perPage = 8;
    public $sortField = 'id';
    public $search = '';
    public $from_group_id = '';
	public $select = ''; 

    public function render()
    {
    	$group_id = session()->get('group_id', '');
        
        return view('livewire.switching', [
    	 	'group_id' => Group::select($this->select),
            'groups' => Group::index(),
            'posts' => Post::search($this->search, end($group_id), $this->from_group_id)
            ->paginate('8'),
            'current_groups' => Group_list::getGroupByParent(end($group_id))
            ->paginate('8'),
            'group_quantity_posts' => Group::quantity(end($group_id))
        ]);
    }
}
