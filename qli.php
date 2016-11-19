<?php
/*
	Qli 可能是最萌的 PHP Mysqli CURD 框架(只是個類)

	by: HaradaShino 原田詩乃

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
			//數據庫基本信息
			$this->host=$db_info["host"];
			$this->user=$db_info["user"];
			$this->pwd=$db_info["pwd"];
			$this->name=$db_info["name"];
			$test_result = $this->conncet_test($this->host,$this->user,$this->pwd,$this->name);
			
			if($test_result){
				$v_return = true;
			}else{
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
		/*
			mysqli_connect_errno()." ".mysqli_connect_error();
		*/
		mysqli_close($dbc);
		return true;
	}

	private function connect(){
		$dbc = mysqli_connect($this->host,$this->user,$this->pwd,$this->name)or die($this->error('<p STYLE="color:red">MySqli數據庫連失敗，看看天然呆的數據庫基本信息是不是錯了喵！</p><br>'.mysqli_connect_errno()." ".mysqli_connect_error()));
		$this->dbc = $dbc;
		return $dbc;
	}
	//基礎CURD操作看對象方法名字相信您會明白！
	public function select(){

	}
	public function insert($table,$arr){
		$dbc = $this->connect();
		
		//初始化SQL插入語句前部分
		$query = "INSERT INTO `".$table."`";
		
		$insert_key = "";	//鍵
		$insert_value = "";	//值

		$arr_key = array(); 
		$arr_value = array();

		foreach($arr as $key => $value){
			array_push($arr_key,$key);
			array_push($arr_value,$value);
		}
		
		$insert_key = implode("`,`",$arr_key);
		$insert_key = "`".$insert_key."`";
		$insert_value = implode("','",$arr_value);
		$insert_value = "'".$insert_value."'";
		
		$query = $query."(".$insert_key.") VALUES (".$insert_value.")";

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



}

$a = [	"host"=>"127.0.0.1",
		"user"=>"root",
		"pwd"=>"root",
		"name"=>"qli"
		];
$test = new Qli($a);
$arr = [	"u_name"=>"蝦米",
			"u_pwd"=>"xiamispassword",
			"u_info"=>"Uaasss"
		];

//$test->insert("tb_user",$arr);




?>