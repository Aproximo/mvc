<?php

class Message extends Model {

	public function save($data, $id = null){
		if( !isset($data['name']) || !isset($data['email']) || !isset($data['message']) ){echo "False";
			return false;
		}

		$id = (int)$id;
		$name = $this->db->escape($data['name']);
		$email = $this->db->escape($data['email']);
		$message = $this->db->escape($data['message']);

		if ( !$id ){ // add new record
			$sql = "
				insert into messages
					set name = '{$name}',
						email = '{$email}',
						messages = '{$message}'
				";
		} else {
			$sql = "
				update messages
					set name = '{$name}',
						email = '{$email}',
						messages = '{$message}'
					where id = {$id}
			";
		}

		return $this->db->query($sql);

	}

	public function getList($only_published = false){
        $sql = "Select * from messages where 1";
        if( $only_published ){
            $sql = " and is_publeshed = 1";
        }
        return $this->db->query($sql);
    }

}