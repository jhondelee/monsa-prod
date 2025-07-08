<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentCommissionItem extends Model
{
    protected $table = 'agent_commission_items';  

    public $timestamps = false;

        public function agent_commission()
    {
        return $this->belongsTo('App\agentcommission', 'id', 'agent_commission_id');
    }

}
