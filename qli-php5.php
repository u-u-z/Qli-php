<?php
/*
	Qli 可能是最萌的 PHP Mysqli CURD 框架(只是個類) 

	by: HaradaShino 原田詩乃 郵箱: i@linux.dog

	這玩意寫的就跟開玩笑一樣）
*/

class Qli{

	//開發者模式
	protected $debug_mode = true;
	
	//高安全模式(数据库输入输出将会使用base64)
	protected $high_safe_mode = false;
	
	//數據庫基本信息
	private $host;		/*若是有端口變動請根據需求自行修改*/
	private $user;
	private $pwd;
	private $name;
	
	private $dbc;
	//構造函數
	public function __construct($db_info){
		if($this->leg_cdb_info($db_info)){
			$v_return = false;
			//var_dump($db_info);
			//數據庫基本信息
			$this->host=$db_info["host"];
			$this->user=$db_info["user"];
			$this->pwd=$db_info["pwd"];
			$this->name=$db_info["name"];
			$test_result = $this->conncet_test($this->host,$this->user,$this->pwd,$this->name);
			if($test_result){
				//echo "連接成功！";
				$v_return = true;
			}else{
				//$this->error("連接失敗！");
				$v_return = false;
			}

		}else{
			$this->error("乃給窩的數據庫基本信息好像不是數組！>_<");
			$v_return = false;
		}
		return "haha";
		
	}

	private function conncet_test($host,$user,$pwd,$name){
		$dbc = mysqli_connect($host,$user,$pwd,$name)or die($this->error('<p STYLE="color:red">MySqli數據庫連失敗，看看天然呆的數據庫基本信息是不是錯了喵！</p><br>'.mysqli_connect_errno()." ".mysqli_connect_error()));
		
		mysqli_close($dbc);
		return true;
	}

	private function connect(){
		$dbc = mysqli_connect($this->host,$this->user,$this->pwd,$this->name)or die($this->error('<p STYLE="color:red">MySqli數據庫連失敗，看看天然呆的數據庫基本信息是不是錯了喵！</p><br>'.mysqli_connect_errno()." ".mysqli_connect_error()));
		$this->dbc = $dbc;
		return $dbc;
	}
	public function sqlquery($str){
		$dbc = $this->connect();

		$result = mysqli_query($dbc,$str) or die('wtf'.mysqli_connect_errno()." ".mysqli_connect_error());
		return $result;

	}

	//基礎CURD操作看對象方法名字相信您會明白！
	public function select(){

	}
	public function refValues($arr){
if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
	$refs = array();
	foreach($arr as $key => $value)
		$refs[$key] = &$arr[$key];
		return $refs;
	}
		return $arr;
}

	public function insert($table,$arr){
		$dbc = $this->connect();
		if (!$dbc) {
			$this->error($err_info);
		}
		//$sec_key $sec_value
		$arr_key = array(); 
		$arr_value = array();
		$arr_value_par = array();
		$value_type = "";

		foreach($arr as $key => $value){
			//$key = $this->safe_filter($key);
			//$value = $this->safe_filter($value);
			array_push($arr_key,$key);
			array_push($arr_value,$value);
			array_push($arr_value_par,"?");

			if (is_string($value)){
				$value_type = $value_type."s";
			}elseif (is_int($value)) {
				$value_type = $value_type."i";
			}elseif(is_double($value)){
				$value_type = $value_type."d";
			}else{
				$value_type = $value_type."s";
			}
		}
		$insert_key = implode("` ,`",$arr_key);
		$insert_key = "`".$insert_key."`";
		$cou_value = count($arr_value);//獲取值得個數

		$insert_value_par = implode(",",$arr_value_par);
		
		$stmt = mysqli_prepare($dbc, "INSERT INTO ".$table." (".$insert_key.") VALUES (".$insert_value_par.")");
		//echo "INSERT INTO ".$table." (".$insert_key.") VALUES (".$insert_value_par.")";
		$php_5_arr_value = array();
		array_push($php_5_arr_value,$stmt);
		array_push($php_5_arr_value,$value_type);
		for($i=0;$i<count($arr_value);$i++){
			//array_push($php_5_arr_value,&$value);
			$php_5_arr_value[]=&$arr_value[$i];
		}

		call_user_func_array('mysqli_stmt_bind_param',$php_5_arr_value);
		mysqli_stmt_execute($stmt);//執行
		mysqli_stmt_close($stmt);//事後
		return $dbc;
		/*
		$php_5_arr_value = array();
		$a = "32dfff22";
		$b = "32212";
		$c = "32fqwf22";
		$d = "32qwd22";
		//$php_5_arr_value = [$stmt,"ssss",$a,$b,$c,$d];
		//call_user_func_array('mysqli_stmt_bind_param',$php_5_arr_value); 
		mysqli_stmt_bind_param($stmt,"ssss",$a,$b,$c,$d);
		

		*/
		//only php 7
		//mysqli_stmt_bind_param($stmt,"sss",$a,$b,$c);
		//call_user_func_array('var_dump',array($this->ret("swdw")));
		/*
		$wwwarr = array($a,$b,$c);
		$ab = &$a;
		$php_5_arr_value[] = $stmt;
		$php_5_arr_value[] = "sss";
		$php_5_arr_value[] = &$a;
		$php_5_arr_value[] = &$b;
		$php_5_arr_value[] = &$c;
		*/

		
		//$wwwarr  = [$this->ret($stmt),$this->ret("sss"),$this->ret($a),$this->ret($b),$this->ret($c)]
		//mysqli_stmt_bind_param($stmt, $value_type,$arr_value[0],$arr_value[1],$arr_value[2],$arr_value[3]);
		//mysqli_stmt_bind_param($stmt, $value_type, ...$arr_value);//这是一个语法糖
		
		/*



		$arr_key = array(); 
		$arr_value = array();

		foreach($arr as $key => $value){
			$key = $this->safe_filter($key);
			$value = $this->safe_filter($value);
			array_push($arr_key,$key);
			array_push($arr_value,$value);
		}
		
		$insert_key = implode("`,`",$arr_key);
		$insert_key = "`".$insert_key."`";
		$insert_value = implode("','",$arr_value);
		$insert_value = "'".$insert_value."'";
		
		$query = $query."(".$insert_key.") VALUES (".$insert_value.")";
		echo $query;
		//$result = mysqli_query($dbc,$query) or die('wtf'.mysqli_connect_errno()." ".mysqli_connect_error());*/
	}
	
	public function ret($con){
		return $con;
	}
	public function del_row($table,$where){
		//DELETE FROM table WHERE id='21'
		//$where = ["id,="=>21]
		
		$dbc = $this->connect();
		$query = "DELETE FROM `".$table."`";
		$query = $query." WHERE";
		$arr = $this->format_con($where);
		
		$del_key = $arr[0];
		$del_con = $arr[1];
		$del_value = $arr[2];
		
		if(is_int($del_value)){
			$query = $query." ".$del_key." ".$del_con."".$del_value;
		}else{
			$query = $query." ".$del_key." ".$del_con."'".$del_value."'";
		}

		echo $query;

		$result = mysqli_query($dbc,$query) or die('wtf'.mysqli_connect_errno()." ".mysqli_connect_error());
		//测试完成！
	}

	public function update($table,$data,$where){
		//$data = ["name"=>"ss","pwd"=>"1213"]
		$dbc = $this->connect();
		
		$query = "UPDATE `".$table."` SET";

		$set_value = "";
		$arr_set = array();

		foreach($data as $key => $value){
			if(!is_int($value)){
				$value = "'".$value."'";
			}
			$set_value = "`".$key."` = ".$value;
			array_push($arr_set,$set_value);
		}

		$set = implode(" , ",$arr_set);

		$arr = $this->format_con($where);

		$set_key = $arr[0];
		$set_con = $arr[1];
		$set_value = $arr[2];

		if(!is_int($set_value)){
			$set_value = "'".$set_value."'";
		}
		/*
			UPDATE  `tb_user` SET  `u_name` =  '蝦米',`u_pwd` =  'xiamispassword',`u_info` =  '2' WHERE id <  '200'
		*/
		$query = $query." ".$set." WHERE ".$set_key." ".$set_con." ".$set_value;

		//echo $query;
		$result = mysqli_query($dbc,$query) or die('wtf'.mysqli_connect_errno()." ".mysqli_connect_error());

	}

	public function leg_cdb_info($arr){
		//
		$is_leg_arr = false;
		$tmp = is_array($arr)&&(!empty($arr)) ? $is_leg_arr = true : $is_leg_arr = false;
		$bRet = !empty($arr["host"])&&!empty($arr["user"])&&!empty($arr["pwd"])&&!empty($arr["name"]) ? $is_leg_arr = true : $is_leg_arr = false;
		return $is_leg_arr;
	}

	private function error($err_info){
		if ($this->debug_mode == true){
			die("<h1>:(</h1> <h2>好像啥玩意錯了...</h2> <p>".$err_info."</p>");
		}else{
			//You can logger it in here...(嗯要是生產環境的話這裡貌似管不到了:)
		}
	}

	private function safe_filter($str){
		$str = strip_tags(trim($str));
		$str = htmlentities($str,ENT_QUOTES,'UTF-8');
		$str = str_replace(
			array('\\','script','expression'),
			array('&#x005c','script','expression')
			);
		$str = addslashes($str);
		$str = $this->safe_sql_filter($str);

		return $str;
	}

	private function safe_sql_filter($str){
		$str = mysqli_real_escape_string($this->dbc,trim($str));
		if($this->high_safe_mode){
			$str = base64_encode($str);
		}
		return $str;
	}

	private function format_con($arr){
		//用于解析where的判断条件
		//["id,="=>1]
		//where id = 11
		foreach($arr as $key => $value){
			$arr_key = $key;
			$arr_value = $value;
		}
		$condition_arr = explode(",",$arr_key);
		$where_key = $condition_arr[0];
		$where_con = $condition_arr[1];
		$where_value = $arr_value;
		$return_arr = [$where_key,$where_con,$where_value];
		return $return_arr;
	}


}
/*
$a = [	"host"=>"127.0.0.1",
		"user"=>"root",
		"pwd"=>"root",
		"name"=>"qli"
		];
$test = new Qli($a);

$arr = [	"u_name"=>"a",
			"u_pwd"=>"1",
			"u_info"=>"q"
		];

$test->insert("tb_user",$arr);

//$del_arr = ["id,>"=>"2"];
//$test->del_row("tb_user",$del_arr);

//$test->update("tb_user",$arr,$del_arr);
*/
?>