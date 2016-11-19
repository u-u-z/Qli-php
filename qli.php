<?php
/*
	Qli 可能是最萌的 PHP Mysqli CURD 框架(只是個類)

	by: HaradaShino 原田詩乃

*/

class Qli{

	//開發者模式
	protected $debug_mode = true;
	
	//高安全模式(insert使用base64)
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

		/*	echo "<br><br>";
			var_dump($test_result);
			echo "合法數據";
			
			var_dump($this->host);
			var_dump($this->user);
			var_dump($this->pwd);
			var_dump($this->name);*/
		}else{
			$this->error("乃給窩的數據庫基本信息好像不是數組！>_<");
			$v_return = false;
		}
		return "haha";
		/*$all_result = "";//反饋給return用的沒啥大用！
		if($this->leg_cdb_info($db_info)){
			
			$this->host=$db_info["host"];
			$this->user=$db_info["user"];
			$this->pwd=$db_info["pwd"];
			$this->name=$db_info["name"];

			$test_result = $this->conncet_test($this->$host,$this->$user,$this->$pwd,$this->$name);
			//return //把return寫到if...else裡面不是好習慣喲！
			if($test_result){
				$all_result = "成功調用！";
			}else{
				$all_result = "失敗的調用TAT";
			}
		}else{
			$this->error("一定要按照要求給我數組喲~ > o <");
			$all_result = "失敗咯";
		}
		*/
		
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
		//echo "-----------------------<br>表名稱:".$table."<br>";
			//var_dump($test_result);
			var_dump($dbc);
		//初始化SQL插入語句前部分
		$query = "INSERT INTO `".$table."`";
		
		$insert_key = "";	//鍵
		$insert_value = "";	//值

		$arr_key = array(); 
		$arr_value = array();

		foreach($arr as $key => $value){
			//echo "|	".$key."	|	".$value."<br>";
			array_push($arr_key,$key);
			array_push($arr_value,$value);
		}
		/*
		var_dump($arr_key);
		var_dump($arr_value);
		*/
		$insert_key = implode("`,`",$arr_key);
		$insert_key = "`".$insert_key."`";
		$insert_value = implode("','",$arr_value);
		$insert_value = "'".$insert_value."'";

		echo "<br>要插入數據庫的key值<br>".$insert_key."<br>";
		echo "<br>要插入數據庫的value值<br>".$insert_value."<br>";

		$query = $query."(".$insert_key.") VALUES (".$insert_value.")";

		echo "<br>最後的SQL語句偽:<br>".$query;
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
		"name"=>"test"
		];
$test = new Qli($a);
$arr = [	"u_name"=>"蝦米",
			"u_pwd"=>"xiamispassword",
			"u_info"=>"UM、、、、"
		];
$test->insert("tb_user",$arr);
//
/*
echo $test->get_host();
echo "<br>";
echo $test->testq();

/*
調試日誌 
--2016-11-18--

Notice: Undefined variable: base_info in D:\phpStudy\WWW\qli.php on line 22

Fatal error: Uncaught Error: Cannot access empty property in D:\phpStudy\WWW\qli.php:22 Stack trace: #0 D:\phpStudy\WWW\qli.php(77): Qli->__construct(Array) #1 {main} Next Error: Cannot access empty property in D:\phpStudy\WWW\qli.php:22 Stack trace: #0 D:\phpStudy\WWW\qli.php(77): Qli->__construct(Array) #1 {main} thrown in D:\phpStudy\WWW\qli.php on line 22--

不改錯了，先睡覺！= =
*/

?>