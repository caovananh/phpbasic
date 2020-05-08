<?php
    use Rakit\Validation\Validator;

    class Product extends Controller{
        public  $url = BASE_PATH.'index.php?controller=product&action=index';
        public function __construct()
        {
            parent::__construct();
            $this->db_product = $this->db('Product_Model');
        }
        public function index(){
            $param =[];
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $name = isset($_GET['name']) ? $_GET['name'] : '';

            if($id){
                $param['where'][]= ['id','=',$id];
            }
            if($name){
                $name ="%".$name."%";
                $param['where'][]= ['name','LIKE' ,$name];
            }
            // echo"<pre>";
            // print_r($_POST);
            // echo"</pre>";
            $this->view->data = $this->db_product->getAll($param);
            $this->view->load('product/index');
        }
        public function ChangeStatus(){
            $id =isset($_GET['id']) ? $_GET['id'] : '';
            $status =isset($_GET['status']) ? $_GET['status'] : '';
            echo $status;
            if($id>0 && $status != ''){
                $status = ($status == 1 ) ? 0 : 1 ;
                $sql = "UPDATE product SET status = $status WHERE id = $id";
                if($this->db_product->execute($sql)){
                $url = BASE_PATH.'index.php?controller=product&action=index';
                 header('location:'.$url);
                 
                }

            }
        }
        public function add(){
            $errors ='';
            if(isset($_POST['submit'])){
                $validator = new Validator;
                $validation = $validator->make($_POST , [
                    'name'                  => 'required|min:5',
                    'price'                 => 'required|numeric',
                ]);

                $validation->setMessages([
                        'name:required' => 'Tên sản phẩm không được rỗng',
                        'price:required' => 'Giá sản phẩm không được rỗng',
                        'numeric' => 'Giá sản phẩm phải là số',
                ]);
                $validation->validate();
                if($validation->fails()){
                    $errors=$validation->errors();
                    $errors= $errors->firstOfAll();
                }else{
                    $data =[
                        'status'=>$_POST['status'],
                        'name' =>$_POST['name'],
                        'category_id' =>$_POST['category_id'],
                        'price'=>$_POST['price'],
                        'detail'=>$_POST['detail'],
                        'description'=>$_POST['description'],
                        'created' =>time(),
                    ];
                    $this->db_product->insert($data);
                    header('location:'.$this->url);
                }    

            }   

            $db = $this->db('Category_Product_Model');
            
            $this->view->list_category_product = $db->getAll();
            $this->view->errors = $errors ;

            $this->view->load('product/add');
        }

        public function edit(){
            $id = isset($_GET['id'])?$_GET['id'] :'';
            $errors ='';
            $isProduct = $this->db_product->isExistRecord([['id','=',$id]]);
            if($isProduct<=0){
                header('location:'.$this->url);
            }
            if(isset($_POST['submit'])){
                $validator = new Validator;
                $validation = $validator->make($_POST , [
                    'name'                  => 'required|min:5',
                    'price'                 => 'required|numeric',
                ]);

                $validation->setMessages([
                        'name:required' => 'Tên sản phẩm không được rỗng',
                        'price:required' => 'Giá sản phẩm không được rỗng',
                        'numeric' => 'Giá sản phẩm phải là số',
                ]);
                $validation->validate();
                if($validation->fails()){
                    $errors=$validation->errors();
                    $errors= $errors->firstOfAll();
                }else{
                    $data =[
                        'status'=>$_POST['status'],
                        'name' =>$_POST['name'],
                        'category_id' =>$_POST['category_id'],
                        'price'=>$_POST['price'],
                        'detail'=>$_POST['detail'],
                        'description'=>$_POST['description'],
                        'created' =>time(),
                    ];
                
                    $this->db_product->edit($id,$data);
                     header('location:'.$this->url);
                }    

            }    
            $db = $this->db('Category_Product_Model');
            
            $this->view->list_category_product = $db->getAll();
            $this->view->errors = $errors ;
            $this->view->item = $this->db_product->getOne([['id','=',$id]]);
            $this->view->load('product/edit');
        }

        public function Delete(){
            $id =isset($_GET['id']) ? $_GET['id'] : '';

            $sql ="DELETE FROM product WHERE id =".$id;
            if($id>0){
                if($this->db_product->execute($sql)){
                    header('location:'.$this->url);
                }
            }
            
        }
    }