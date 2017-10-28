<?php
if (!defined('DRUPAL_ROOT')) define('DRUPAL_ROOT', '../../');
$base_url = 'http://erp.innboost.com';
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
class model {
    public $host = "localhost";
    public $dbpassword = ";x]O9t}rcA,u";
    public $dbuser = "innboost_erp";
    public $dbname = "innboost_innboost";	
	// connect function
	function AMDdbconnect ($host , $user , $pass ,$db){
		$conn = mysqli_connect($host,$user,$pass);
		if (!$conn)
			{
				die('Could not connect: ' . mysql_error());
			}
		mysqli_query($conn,"SET NAMES 'utf8'");
		mysqli_query($conn,'CHARACTER SET utf8 COLLATE utf8_general_ci');
		mysqli_query($conn,"set character_set_server='utf8'");
		//header('Content-type: text/html; charset=UTF-8') ;
		mysqli_select_db($conn,$db);
		return $conn;
	}
	function create_table(){
	    $con  = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
		$sql = "CREATE TABLE IF NOT EXISTS `customer` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` Text NOT NULL,
          `phone` Text NOT NULL,
		  `shourtcut` Text NOT NULL,
          `mail` Text NOT NULL,
		  `facebook` Text NOT NULL,
          `instagram` Text NOT NULL,
		  `website` Text NOT NULL,
		  `comment` Text ,
		  `createdOn` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
          `createdBy` varchar(100) NOT NULL,
		  `status` int(2) NOT NULL DEFAULT '1',
		   PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		 $res = $con->query($sql);
		}
    
	
	function get_customersbyid($id) {
        $con = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql = "select * from customer where id='$id'";
		$r = array();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $con->close();
		echo json_encode($r);
    }
	
    function get_customers() {
        $con = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql = "select * from customer where status=1 ORDER BY id";
		$r = array();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $con->close();
		echo json_encode($r);
    }

    // add new brand 
    function new_customer($name, $phone,$comment,$mail,$facebook,$instagram,$website,$shourtcut) {
        $con = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql = "insert into customer (name,phone,comment,website,mail,facebook,instagram,shourtcut) values('$name','$phone','$comment','$website','$mail','$facebook','$instagram','$shourtcut')";
        if ($con->query($sql) === true) {
		 	$con->close();
            return TRUE;
        } else {
			$con->close();
            echo "Error: " . $sql . "<br>" . $con->error;
        }
       
    }
    // delete brand 
    function delete_customer($id) {
        $con = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql = "UPDATE customer set status=0 WHERE id= $id";
        if ($con->query($sql) === true) {
            $con->close();
			return TRUE;
        } else {
			$con->close();
            echo "Error: " . $con->error;
        }
        
    }

    // update
     function update_customer($name, $phone,$comment,$mail,$facebook,$instagram,$website,$shourtcut, $id) {
	    $oldsellerdata = json_decode($this->get_sellerbyid($id));
		$oldsellerdata = json_decode(json_encode($oldsellerdata[0]), True);
		//print_r($oldsellerdata['id']);
        $mysqli = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $mysqli->autocommit(FALSE);
		if($mysqli->query("insert into customer(name,phone,comment,website,mail,facebook,instagram,shourtcut,status) values('$oldsellerdata[name]','$oldsellerdata[phone]','$oldsellerdata[comment]','$oldsellerdata[website]','$oldsellerdata[mail]','$oldsellerdata[facebook]','$oldsellerdata[instagram]','$oldsellerdata[shourtcut]',0)")===true && $mysqli->query("UPDATE  customer set name='$name',phone='$phone',comment='$comment',website='$website',mail='$mail',facebook='$facebook',instagram='$instagram',shourtcut='$shourtcut' where id='$id'")===true)
        {
			$mysqli->autocommit(TRUE);
			return true ;
		}
		else
			return false ;
	    $mysqli->close();
    }
	
	function get_sellerbyid($id) {
        $con = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql = "select * from customer where status=1 and id = '$id'";
		$r = array();
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $r[] = $row;
            }
        }
        $con->close();
        return json_encode($r);
    }
	// get user by name
	function getuserbyname($name){
		$users = user_load_multiple(array(), array('name' => $name));
        return reset($users);
	}
	
	// new project	
	function new_project($name , $phone  , $mail , $facebook ,  $instagram , $website , $package , $noofpost , $noofad , $price ,$startdate ,$enddate,$shortcut ,$pricetoknow,$AdManage,$contentteam,$desteam,$customerform ,$clientpricing, $customerservice , $Photography ,$cid ) {
	$con  = $this->AMDdbconnect($this->host, $this->dbuser, $this->dbpassword, $this->dbname);
        $sql  = "INSERT INTO projects (name , phone  , mail , facebook ,  instagram , website , package , noofpost , noofad , renoofpost , renoofad , price , reprice , pricetoknow ,startdate ,enddate ,shortcut ,customerform ,clientprice,cid ) values ('$name' , '$phone'  , '$mail' , '$facebook' ,  '$instagram' , '$website' , '$package' , '$noofpost' , '$noofad' , '$noofpost' , '$noofad' , '$price', '$price' , '$pricetoknow' ,'$startdate' ,'$enddate' ,'$shortcut' ,'$customerform' ,'$clientpricing','$cid') ";
		$con->autocommit(FALSE);
		if ($con->query($sql) === true) {
            $last_id = $con->insert_id;
			if ($this->new_accesscontrole($last_id , "project"  ,$AdManage, "AdManage","edit" ,$con) && $this->new_accesscontrole($last_id , "project"  ,$contentteam, "contentteam","edit" ,$con) && $this->new_accesscontrole($last_id , "project"  ,$desteam, "desteam" ,"edit" ,$con) && $this->new_accesscontrole($last_id , "project"  ,$customerservice, "customerservice" ,"edit" ,$con) && $this->new_accesscontrole($last_id , "project"  ,$Photography, "Photography" ,"edit" ,$con)){
				$con->autocommit(TRUE);
				$con->close();
			    return TRUE;
			}else {
            $con->close();
			return FALSE;
            }
        }else {
            $con->close();
			return FALSE;
        } 
		
	}
	
	// new access controle	
	function new_accesscontrole($id , $type  , $allowed , $role_name , $action , $con) {
	    $users = json_decode($allowed);
		global $user;
		$uid = $user->uid;
		$usersids = array();
		foreach ($users as $value){
		    $usersid = json_decode(json_encode($this->getuserbyname($value)), true);
			//$pname = $this->get_pname($id , $type);
			//echo "addNotification&text=$id&type=$value&action=$type&uid=$uid";
			file_get_contents("http://localhost:8081/innboost/amd/php/notifications.php?a=addNotification&text=$id&type=$value&action=$type&uid=$uid",true);
		 	array_push($usersids, $usersid['uid']);
		 }
		//print_r($usersids);
		$us =json_encode($usersids, true);
        $sql  = "INSERT INTO accesscontrol  (pid , type  , allowed , role_name , action) values ('$id' , '$type'  , '$us' , '$role_name' , '$action')";
		if ($con->query($sql) === true) {
			return TRUE;
        } else {
			return FALSE;
        }
	}
	
	
	
}

?>