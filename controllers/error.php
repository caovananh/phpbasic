<?php
    class Errors extends Controller{
        public function process(){
            $this->view->msg ='this page is error';
            $this->view->load('error/error');
        }
    }
?>