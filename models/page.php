<?php

class Page extends Model{

    public function getList ($only_published = false){
        $sql = "Select * from pages where 1";

        if( $only_published ){
            $sql = $sql . " and is_publeshed = 1";
        }
        return $this->db->query($sql);
    }

    public function getSorted($sort = 'id', $by = 'ASC', $only_published = false){
//	    echo "<pre>";
       $num = 3;
       $page = $_GET['page'] ? $_GET['page'] : 1;
       $temp = $this->db->query("SELECT COUNT(*) FROM pages");
       $posts = $temp[0]['COUNT(*)'];
       $total = (($posts - 1) / $num) + 1;
       $total =  intval($total);
       $page = intval($page);
       if(empty($page) or $page < 0) $page = 1;
       if($page > $total) $page = $total;
       $start = $page * $num - $num;

		$sql = "Select * from pages where 1 Order by $sort $by LIMIT $start, $num";

		if( $only_published ){
			$sql = " and is_publeshed = 1";
		}
		$result = $this->db->query($sql);
		$result = [$result, $posts];
		return $result;
	}

	public function getByAlias($alias){
		$alias = $this->db->escape($alias);
		$sql = "select * from pages where alias = '{$alias}' limit 1";
		$result = $this->db->query($sql);
		return isset($result[0]) ? $result[0] : null;
	}

		public function getById($id){
		$id = (int)$id;
		$sql = "select * from pages where id = '{$id}' limit 1";
		$result = $this->db->query($sql);
		return isset($result[0]) ? $result[0] : null;
	}

	public function save($data, $id = null){
        if( !isset($data['title']) || !isset($data['username']) || !isset($data['email']) || !isset($data['task']) ){
            return false;
        }

        $id = (int)$id;
        $username = $this->db->escape($data['username']);
        $title = $this->db->escape($data['title']);
        $email = $this->db->escape($data['email']);
        $is_published = isset($data['is_published']) ? 1 : 0;
        $status = isset($data['status']) ? 1 : 0;
        $task = $this->db->escape($data['task']);
        $picture_name = isset($data['picture_name']) ? $data['picture_name'] : null;

        if ( !$id ){ // add new record
            $sql = "
				insert into pages
					set username = '{$username}',
						title = '{$title}',
						email = '{$email}',
						is_published = '{$is_published}',
						task = '{$task}',
						picture_name = '{$picture_name}',
						status = '{$status}'
				";
        } else {
            $sql = "
				update pages
					set username = '{$username}',
						title = '{$title}',
						email = '{$email}',
						is_published = '{$is_published}',
						task = '{$task}',
						picture_name = '$picture_name',
						status = '{$status}'
					where id = {$id}
			";
        }

        return $this->db->query($sql);


    }

    public function delete ($id){

       if (isset($id)){
           $id = (int)$id;
           $sql = "delete from pages where id = {$id}";
           return $this->db->query($sql);
       }
       return false;

    }
}