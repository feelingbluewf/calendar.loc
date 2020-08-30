<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Group;

class ControlPanel extends Component
{
    public function render()
    {
        return view('livewire.control-panel', [
            'groups' => Group::index()
        ]);
    }
}
