<?php

class PagesController extends Controller {

	public function __construct($data = array()){
		parent::__construct($data);
		$this->model = new Page();
	}

	public function index(){
	    if(isset($_GET['sort'])){
	        $sort = explode('.',$_GET['sort']);
        }
        if (isset($sort) && ($sort[0] == 'username' || $sort[0] == 'email' || $sort[0] == 'status') && ($sort[1] == 'ASC' || $sort[1] == 'DESC')){
            $array = $this->model->getSorted($sort[0], $sort[1]);
        }else {
            $array = $this->model->getSorted();
        }

        $this->data['pages'] = $array[0];
        $this->data['count'] = $array[1];
	}

	public function view(){
		$params = App::getRouter()->getParams();

		if(isset($params[0])){
			$id = strtolower($params[0]);
			$this->data['page'] = $this->model->getById($id);

		}
	}

    public function add(){

	    if ($_POST){

            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                die("Upload failed with error code " . $_FILES['image']['error']);
            }
            $info = getimagesize($_FILES['image']['tmp_name']);

            if ($info === FALSE) {
                die("Unable to determine image type of uploaded file");
            }

            if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
                 die("Not a gif/jpeg/png");
            }

            $time = date("d.m.Y_H-i-s");
            $fileName = "created on - $time ".$_FILES['image']['name'];
            $this->saveFile($fileName);


            if( ($info[0] > 320) || ($info[1]>240) ){
                $this->imageresize("uploads/".$fileName, $fileName,320,240,75);
            }
            $_POST['picture_name'] = $fileName;
            $result = $this->model->save($_POST);

            if ($result){
                Session::setFlash('Pages was saved');
            } else {
                Session::setFlash('Error');
            }
            Router::redirect('/pages/');
        }
    }

	public function admin_index(){
		$this->data['pages'] = $this->model->getList();
	}


	public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result){
                Session::setFlash('Pages was saved');
            } else {
                Session::setFlash('Error');
            }
            Router::redirect('/admin/pages/');
        }

		if ( isset($this->params[0]) ){
			$this->data['page'] = $this->model->getById($this->params[0]);
		} else {
			Session::setFlash("Wrong Page id");
			Router::redirect('/admin/pages/');
		}
	}


    public function admin_delete(){

        if (isset($this->params[0])){
            $result = $this->model->delete($this->params[0]);
            if ($result){
                Session::setFlash('Pages was delete');
            } else {
                Session::setFlash('Error');
            }
        }
        Router::redirect('/admin/pages/');
    }

    private function imageresize($outfile,$infile,$neww,$newh,$quality) {

        $im=imagecreatefromjpeg("uploads/".$infile);
        $im1=imagecreatetruecolor($neww,$newh);
        imagecopyresampled($im1,$im,0,0,0,0,$neww,$newh,imagesx($im),imagesy($im));

        imagejpeg($im1,$outfile,$quality);
        imagedestroy($im);
        imagedestroy($im1);
    }

    private function saveFile($fname = ''){
        $target_path = "uploads/";// where the files will end up this path must be "writable" and "readable" by the web and it's relative to your site folder.
        $target_path = $target_path . $fname; // Set the destination for the file so PHP can move it

        if($fname != ''){
            // the file was sucessfully posted to the web server
            if(!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
               die("The file ". $fname . " hasn't been uploaded and moved to the right place");
            }}
    }
}