<?php
    class Home extends Controller{
        public function index(){
           // $db = $this->db('Product_Model'); 
           // $db = $this->db('Pass_Model');
            
            $this->view->load('home/index');
          
        }
        public function add(){

        }

        public function edit(){
            
        }
      
        
    }