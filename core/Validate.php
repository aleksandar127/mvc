<?php

namespace Core;
use \Core\DB;
use \Core\Input;

class Validate{
    private $passed=true,$errors=[],$db;

    public function __construct(){
        $this->db=new \Core\DB;

    }


    public function check($source,$items=[]){
        $this->errors=[];
        foreach($items as $item=>$rules){
            $item=\Core\Input::sanitize($item);
            $display=$rules['display'];
            foreach($rules as $rule=>$rule_value){
                $value=\Core\Input::sanitize(trim($source[$item]));
                if($rule==='required' && empty($value)){
                    $this->addError(["{$display} is required",$item]);
                }
                else if(!empty($value)){
                    switch($rule){
                        case 'min':
                            if(strlen($value)<$rule_value){
                                $this->addError(["{$display} must be a minimum of {$rule_value} characters.",$item]);
                            }
                        break;
                        case 'max':
                            if(strlen($value)>$rule_value){
                                $this->addError(["{$display} must be a maximum of {$rule_value} characters.",$item]);
                            }
                        break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $matchDisplay=$items[$rule_value]['display'];
                                $this->addError(["{$matchDisplay} and {$display} must match.",$item]);
                            }
                        break;
                        case 'unique':
                            $check=$this->db->query("select {$item} from users where {$item}=?",[$value]);
                            if($check->count()){
                                $this->addError(["{$display} already exists,please choose another {$display}.",$item]);
                            }
                            
                        break;
                        case 'unique_update':
                            $t=explode(',',$item_values);
                            $table=$t[0];
                            $id=$t[1];
                            $query=$this->db->query("select * from {$table} where id!=? and {$item}=?",[$id,$value]);
                            if($query->count()){
                                $this->addError(["{$display} already exists,please choose another {$display}.",$item]);
                            }
                            
                        break;
                        case 'is_numeric':
                            if(!is_numeric($value)){
                                $this->addError(["{$display} has to be a number,please use a numeric value.",$item]);
                            }
                            
                        break;
                        case 'valid_email':
                            if(!filter_var($value,FILTER_VALIDATE_EMAIL)){
                                $this->addError(["{$display} must be valid email adress.",$item]);
                            }
                            
                        break;
                    }
                }
            }
        }


    }



    public function addError($error=[]){

        $this->errors[]=$error;
        if(count($this->errors)===0){
            $this->passed=true;
        }
        else{
            $this->passed=false;
        }

    }



    public function errors(){
        return $this->errors;
    }

    public function passed(){
        return $this->passed;
    }

    public function displayErrors(){
       $html="<ul class='bg-danger'>";
       foreach($this->errors as $error){
            $html.="<li class='text-dark'>".$error[0]."</li>";
            $html.="<script>jQuery('document').ready(function(){jQuery('#".$error[1]."').parent().closest('div').addClass('has-error');});
        </script>";
       }
       $html.="</ul>";
     
       return $html;
    }













}