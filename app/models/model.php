<?php

namespace App\Models;
use Core\DB;
use \App\Models\Users;

class Model{

protected $db,$table,$ColumnNames,$softDelete=false,$modelName;
public $id;

public function __construct($table){
    $this->db= new \Core\DB;
    $this->table=$table;
    $this->setColumns(); 
    $this->modelName=str_replace(' ','',ucwords(str_replace('_',' ',$this->table)));

}


public function setColumns(){
    
    $columns=$this->getColumns();
    foreach($columns as $column){
        $this->ColumnNames[]=$column->Field;
    }

}

public function getColumns(){
    return $this->db->getColumns($this->table);
}


public function find($params=[]){
    $params=$this->softDelete($params);
    $results=[];
    $resultsQuery=$this->db->find($this->table,$params);
    $model='\App\Models\\'.$this->modelName;
    foreach($resultsQuery as $result){
        $obj=new $model($this->table);
        $obj->populateObjData($result);
        $results[]=$obj;
    }
    return $results;
}



public function findFirst($params=[]){
    $params=$this->softDelete($params);
    
    $resultQuery=$this->db->findFirst($this->table,$params);
    $model='\App\Models\\'.$this->modelName;
    
    //$result=new $this->modelName($this->table);
    $result=new $model($this->table);//dnd($resultQuery);/////
    if($resultQuery){
        $result->populateObjData($resultQuery);
    }
    return $result;
}

public function populateObjData($result){
    foreach($result as $key=>$val){
        $this->$key=$val;
    }
}


public function findById($id){
    return $this->db->findFirst(['condition'=>'id=?','bind'=>$id]);
}


public function insert($fields){
    if(empty($fields))return false;
    return $this->db->insert($this->table,$fields);   
}

public function update($id,$fields){
    if(empty($fields) || $id=='') return false;
    return $this->db->update($this->table,$id,$fields);
}

public function delete($id){
    if($id=='' && $this->id=='')return false;
    $id= ($id=='')?$this->id:$id;
    if($this->softDelete){
        return $this->update($id,['deleted'=>1]);
    }
    return $this->db->delete($this->table,$id);   
}

public function query($sql,$bind=[]){
    return $this->db->query($sql,$bind); 

}


public function save(){
    $fields=[];
    foreach($this->ColumnNames as $column){
        $fields[$column]=$this->$column;
    }
    if(property_exists($this,'id') && $this->id !=''){
        return $this->update($this->id,$fields);
    }
    else{
        return $this->insert($fields);
    }
}

public function data(){
    $data=new stdClass();
    foreach($this->ColumnNames as $column){
        $data->column=$this->column;
    }
    return $data;
}

public function assign($params){
    if(!empty($params)){
        foreach($params as $key=>$value){
            if(in_array($key,$this->ColumnNames)){
                $this->$key=sanitize($value);
            }
        }
        return true;
    }
    return false;

}

protected function softDelete($params){
    if($this->softDelete){
        if(array_key_exists('condition',$params)){
            if(is_array($params['condition'])){
                $params['condition'][]='deleted !=1';
            }else{
                $params['condition'].='AND deleted !=1';
            }
        }else{
            $params['condition']='deleted !=1';
        }
    }
    return $params;
}







}