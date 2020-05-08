<?php
    class DB{
        private $conn;
        protected $table = 'product' ;

        public function __construct()
        {
            $connect = @mysqli_connect('localhost','root','','my_table_product');
            if($connect){
                $this->conn =$connect;
                mysqli_set_charset($connect,'utf8');
            }
        }

        public function getAll($params=[]){
            $db = null;
            $sql='';
            $str_select = '*';
            $str_where ='';
            $str_limit ='';

            if(isset($params['select']) && !empty($params)){
                $str_select = implode(",",$params['select']) ;
            }
            if(isset($params['where']) && !empty($params['where'])){
                $str_where =$this->createSqlWhere($params['where']);
            }
            
            if(isset($params['limit']) && !empty($params['limit'])){
                $str_limit = 'LIMIT'.' '.implode(",",$params['limit']);
            }
           
           $sql ="SELECT $str_select FROM $this->table  $str_where  $str_limit";
          // echo $sql;
           $result = mysqli_query($this->conn,$sql);
           if($result){
               while($item = mysqli_fetch_object($result)){
                   $db[] = $item;
               }
           }
           return $db;
        }

        public function getOne($params=[]){
            $sql='';
            $str_select = '*';
            $str_where ='';

            if(isset($params) && !empty($params)){
                $str_where =$this->createSqlWhere($params);
                $sql ="SELECT $str_select FROM $this->table  $str_where ";
              //  echo $sql;
                $result = mysqli_query($this->conn,$sql);
            
                return mysqli_fetch_object($result) ;
            }
        }
        public function createSqlWhere($where =[]){
            $str_where ='';
            if(isset($where) && !empty($where)){
                $str_where ='WHERE ';
                foreach($where as $key=>$row){
                    if(!empty($row)){
                        $str_where.= $row[0].' '.$row[1].' '.(is_numeric($row[2])?$row[2]:'\''.$row[2].'\'');
                    }
                    if(count($where) -1 != $key){
                        $str_where.=' AND ';
                    }
                }
            }
            return $str_where;
        }
        // DELETE FROM category_product WHERE id =10
        public function delete($value){
          if(!is_array($value)){
              if(!empty($value)){
                  $sql = "DELETE FROM $this->table WHERE id ='$value' ";
                  return mysqli_query($this->conn,$sql);
              }
          }else{
            //   foreach($value as $key){
            //     $sql ="DELETE FROM $this->table WHERE id ='$key'";
            //     mysqli_query($this->conn,$sql);
            //   }
            //c2 : enhance
              $sql ="DELETE FROM $this->table WHERE id IN "."(".implode(",",$value).")";
              mysqli_query($this->conn,$sql);
          }
        }

        // check exist a row(record) in database
        public function isExistRecord($where){
            //SELECT * FROM user WHERE $str_where
            $str_where ='';
            if(isset($where) && !empty($where)){
                $str_where = $this->createSqlWhere($where);
                $sql ="SELECT * FROM $this->table  $str_where";
                $result =mysqli_query($this->conn,$sql);
                return mysqli_num_rows($result);
                
            }
           
        }
        public function execute($sql ){
            if($sql){
                echo $sql;
                return mysqli_query($this->conn,$sql);
            }else return false;
            
        }
        public function insert($data =[]){
            if(count($data)>0){
               $str_col = '';
               $str_val='';
                foreach($data as $key =>$row){
                    $str_col .= ','.$key;
                    $str_val .= ','.'\''.$row.'\'';
                }
                $str_col =substr($str_col,1);
                $str_val =substr($str_val,1);
                $sql = "INSERT INTO $this->table ($str_col) VALUES ($str_val)";
                return $this->execute($sql);
                
            }
        
        }
        public function edit($id,$data =[]){
            if(count($data)>0){
               $str_val='';
                foreach($data as $key =>$row){
                    if(!is_numeric($row)){
                        $str_val .= ','.$key.'='.'\''.$row.'\'';
                    }else{
                        $str_val .=','.$key.'='.$row;
                    }
                   
                }
                     $str_val =substr($str_val,1);
               
                 $sql = "UPDATE $this->table SET $str_val  WHERE id=$id";
                return $this->execute($sql);
                
            }
        
        }
    }
?>