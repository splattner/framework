<?php

namespace splattner\framework;

class Model {
	
	public $table;
    public $pk = "id";
	
	public $fields;

	protected $db;
    protected $session;

	public function __construct() {
		$this->db = Application::getInstance("db");
        $this->session = Application::getInstance("session");

		$this->fields = array();

	}

	public function update($where) {
		
		$sql = "UPDATE `" . $this->table . "` SET ";
        $values = array();

		foreach($this->fields as $key => $value) {
            $sql .= $key . " = ?, ";
            $values[] = $value;

        }
		$sql = substr($sql, 0, -2); // Remove last ","

        $sql .= " WHERE ";

        $i = 0;
        foreach($where as $key => $value)
        {
            $i++;
            $sql .= $key . " = ?";
            $values[] =  $value;

            if(count($where) > 1 && $i < count($where)) {
                $sql .= " AND ";
            }

        }

        $sql = $this->db->Prepare($sql);
		$this->db->Execute($sql, $values);

	}
	
	public function insert() {
		
		$sql = "INSERT INTO `" . $this->table . "` (";
        $values = array();
		
		foreach($this->fields as $key => $value)
		{
			$sql .= $key . ", ";
		}
		$sql = substr($sql, 0, -2); // Remove last ","
		$sql .= ") VALUES (";
		
		foreach($this->fields as $key => $value)
		{
			$sql .= "? , ";
            $values[] =  $value;
		}
		$sql = substr($sql, 0, -2); // Remove last ","
		$sql  .= ")";

        $sql = $this->db->Prepare($sql);
		$this->db->Execute($sql, $values);
		
		return $this->db->Insert_ID();
	}
	
	public function getRS($where = array(), $orderby = array(), $filter = array())
    {


        $sql = "SELECT ";


        if (count($filter) > 0) {
            foreach($filter as $f) {
                $sql .= $f . ", ";

            }
            $sql = substr($sql, 0, -2); // Remove last AND
        } else {
            $sql .= "*";
        }


        $sql .= " FROM `" . $this->table . "`";

        $whereValues = array();

        if (count($where) > 0) {
            $sql .= " WHERE";
            foreach ($where as $key => $value) {
                $sql .= " " . $key . " ? AND";
                $whereValues[] = $value;
            }
            $sql = substr($sql, 0, -3); // Remove last AND
        }

        if (count($orderby) > 0) {
            $sql .= " ORDER BY";
            foreach ($orderby as $key => $value) {
                $sql .= " " . $key . " " . $value . ",";
            }
            $sql = substr($sql, 0, -1); // Remove last AND
        }

        $sql = $this->db->Prepare($sql);


        if (count($where) > 0) {
            return $this->db->Execute($sql, $whereValues);
        } else {
            return $this->db->Execute($sql);
        }

	}
	
	public function delete($where) {

		$whereValues = array();

		$sql = "DELETE FROM `" . $this->table . "` WHERE";

		foreach($where as $key => $value)
		{
			$sql .= " " . $key . " = ? AND";
			$whereValues[] =  $value;
		}
		$sql = substr($sql, 0, -3); // Remove last AND

        $sql = $this->db->Prepare($sql);
		$this->db->Execute($sql, $whereValues);
	}
	
	public function __get($name) {
		return $this->fields[$name];
	}
	
	public function __set($name, $value) {
		$this->fields[$name] = $value;
	}


	public function api_get($id) {

        $this->db->setFetchMode(ADODB_FETCH_ASSOC);

		if($id > 0) {
			$where = array($this->pk . " =" => $id);
		} else {
			$where = array();
		}

        echo json_encode($this->getRS($where)->getArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
    }
    public function api_post($data) {

        $this->fields = $data;
        $id = $this->insert();

        $this->api_get($id);
    }


    public function api_put($id, $data) {

        $this->fields = $data;
        $where = array($this->pk => $id);
        $this->update($where);

        $this->api_get($id);
    }


    public function api_delete($id) {

        $where = array($this->pk => $id);
        $this->delete($where);
    }
}
?>
