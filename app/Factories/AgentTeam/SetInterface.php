<?php

namespace App\Factories\AgentTeam;

interface SetInterface {
    
     public function index();

     public function sub_agents($mainID);

     public function agentsEarned($empID);
     
}
