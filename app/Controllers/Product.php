<?php 
namespace App\Controllers;

class Product extends BaseController
{
	public $data = [];
	protected $db = null;
	protected $builder;
	public function __construct(){
		$this->db = \Config\Database::connect();
		$this->builder= $this->db->table('product');
    }
	public function index()
	{
		
		$builder = $this->builder
						->select();
						//->where('id', 222261)
						//->like('name', 'may')
						//->get();
		//filer
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$name = isset($_GET['name']) ? $_GET['name'] : '';
		
        if($id){
            $builder->where('id',$id);
        }
        if($name){
            $builder->like('name', $name);
		}
		$query = $builder->get();
		$this->data['list'] = $query->getResult();
		return view('product/index', $this->data);
	}
	public function add(){
		helper(['form', 'url']);
		
        if(isset($_POST['submit'])){
			$validation =  \Config\Services::validation();
			
			$validation->setRules([
				'name' => 'required|min_length[3]',
				'price' => 'required|min_length[3]'
			],
			[   // Errors
				'name' => [
					'required' => 'where is name?',
				],
				'price' => [
					'min_length' => 'too short~~~~~~.~~~~'
				]
			]
		);


            if ($validation->withRequest($this->request)->run())
			{
				$data =[
                    'status'=>$_POST['status'],
                    'name' =>$_POST['name'],
                    'price'=>$_POST['price'],
                    'detail'=>$_POST['detail'],
                    'description'=>$_POST['description'],
                    'created' =>time(),
				]; 	
				echo '<pre>';
				$this->builder->insert($data);
				header('location:'.base_url().'/public');die();
			}
			else
			{
				$this->data['validation'] = $validation->getErrors();
			}	     
        }
        return view('product/add', $this->data);
        
	}

	//edit
	public function edit(){
		$id =isset($_GET['id']) ? $_GET['id'] : '';
        if(isset($_POST['submit'])){
            
                $data =[
                    'status'=>$_POST['status'],
                    'name' =>$_POST['name'],
                    'price'=>$_POST['price'],
                    'detail'=>$_POST['detail'],
                    'description'=>$_POST['description'],
                    'created' =>time(),
				]; 
				
       		 	$this->builder->where('id', $id);
				$this->builder->update($data);
				header('location:'.base_url().'/public');die();
					     
		}
        return view('product/update', $this->data);
        
	}


	public function delete(){
		$id =isset($_GET['id']) ? $_GET['id'] : '';
        $this->builder->where('id', $id);
        $this->builder->delete();
	  header('location:'.base_url().'/public');die();
        
	}
	public function changeStatus(){
        $id =isset($_GET['id']) ? $_GET['id'] : '';
        $status =isset($_GET['status']) ? $_GET['status'] : '';
        if($id>0 && $status != ''){
            $status = ($status == 1 ) ? 0 : 1 ;
            $this->builder->where('id', $id);
            $data = array(
                'status' => $status
        );
            $this->builder->update( $data);
			header('location:'.base_url().'/public');die();
           // print_r($this->db->last_query());die();
        }
    }
	//--------------------------------------------------------------------

}
