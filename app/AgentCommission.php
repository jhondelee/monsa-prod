<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentCommission extends Model
{
    protected $table = 'agent_commission';  

    public function employees()
    {
        return $this->belongsTo('App\Employee', 'id', 'employee_id');
    }
}
