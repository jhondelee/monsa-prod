<?php

namespace App\Factories\Returns;

interface SetInterface {
    
     public function getindex();
     
     public function referenceNo();

     public function getsoitems($SoNum);

     public function getreturnitems($id);

     public function getreturntosupplier();
}
