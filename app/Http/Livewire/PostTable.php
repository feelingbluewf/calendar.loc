<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Models\Group_list;

class PostTable extends Component
{
    use WithPagination;
    public $perPage = 8;
    public $search = '';
    public $from_group_name = '';

    public function render()
    {  
        $group_id = session()->get('group_id', '');
        
        return view('livewire.post-table', [
            'posts' => Post::search($this->search, end($group_id), $this->from_group_name)
            ->paginate($this->perPage),
            'groups' => Group_list::getGroupByParent(end($group_id))    
        ]);
    }
}
