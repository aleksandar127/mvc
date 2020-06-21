<?php 

namespace Core;
use \PDO;


class DB {

    public static $conn;
    public $query,$result,$lastInsertId,$count,$error;


    public function __construct(){
        if(self::$conn)
            return self::$conn; 
        $this->connect();
        
    }

    public function connect(){

        

        try{
            
            self::$conn = new PDO('mysql:host='.HOST.';dbname='.DBNAME.';charset=utf8mb4', DBUSERNAME, DBPASS);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
        }catch(PDOException $e){
			
			die($e->getMessage());
        }
	}



    public function query($sql,$param=[]){
        
        $result=self::$conn->prepare($sql);
        
        if($param){
            $i=1;
            foreach($param as $values){
                $result->bindValue($i,$values);
                $i++;
            }
        }
        $result->execute();
        $this->lastInsertId=self::$conn->lastInsertId();
        $this->count=$result->rowCount();
        if(stripos($sql,'select')!==false || stripos($sql,'SHOW')!==false){
             $this->result = $result->fetchAll(PDO::FETCH_OBJ);
             return $this;
        }
        else{
            if($result->rowCount())
                return true;
            return false;    
        } 
        }




    public function insert($table,$fields=[]){
        $values=array_values($fields);
        $fieldsPart='('.implode(',',array_keys($fields)).') ';
        $ValuesPart='';
        foreach($fields as $key){
             $ValuesPart.='?,';
        }
        $ValuesPart='('. rtrim($ValuesPart,',').')';
        $sql="insert into {$table} {$fieldsPart} values {$ValuesPart}";
        return $this->query($sql,$values);
    }

    public function update($table,$id,$fields=[]){
        $values=array_values($fields);
        array_push($values,$id);
        $keys=array_keys($fields);
        $fieldsPart='';
        foreach($keys as $key){
            $fieldsPart.=$key.'=?,';
        }
        $fieldsPart=rtrim($fieldsPart,',');
        $sql="update {$table} set {$fieldsPart} where id=?";
        return $this->query($sql,$values);
    }
    
    public function delete($table,$id){

        $sql="delete from {$table} where id={$id}";
        return $this->query($sql);

    }

    public function first(){

        return (!empty($this->result))?$this->result[0]:false;
    }

    public function getColumns($table){

        return $this->query("SHOW COLUMNS FROM {$table}")->results();
    }
    public function results(){

        return $this->result;
    }
    public function count(){
        
        return $this->count;
    }
    public function lastInsertId(){
        
        return $this->lastInsertId;
    }

    public function _read($table,$params=[]){
        $conditionString='';
        $order='';
        $limit='';
        $bind=[];

        if(isset($params['condition'])){
            if(is_array($params['condition'])){
                foreach($params['condition'] as $condition ){
                    $conditionString.=' '.$condition.' AND';
                }
            $conditionString=rtrim($conditionString,'AND');
            }
        
            else{
                $conditionString=$params['condition'];
            }
            $conditionString='WHERE '.$conditionString;
        }
    

        if(array_key_exists('order',$params)){

            $order='ORDER BY '.$params['order'];
        }

        if(array_key_exists('bind',$params)){

            $bind=$params['bind'];
        }

        if(array_key_exists('limit',$params)){

            $limit='LIMIT '.$params['limit'];
        }

        $sql="SELECT * FROM {$table} {$conditionString} {$order} {$limit}";
       
       if(!is_array($bind))$bind=array($bind);
        if($this->query($sql,$bind)){
            if($this->count()){
                return true;
            }
            return false;
        };
    }



    public function find($table,$params=[]){
        if($this->_read($table,$params)){
            return $this->results();
        }
    }

    public function findFirst($table,$params=[]){
        if($this->_read($table,$params)){
            return $this->first();
        }
    }
















}



  
 

 
 


