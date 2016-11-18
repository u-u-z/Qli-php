<?php
/*
	Qli 可能是最萌的 PHP Mysqli CURD 框架(只是個類)

	by: HaradaShino 原田詩乃
	
	E-mail: i@linux.dog
*/
class Qli{

	//開發者模式
	protected $debug_mode = false;
	
	//高安全模式(insert使用base64)
	protected $high_safe_mode = false;
	
	private $base_info = ["host" => "","user" => "","pwd" =>"","name" =>""];

	public function __construct($db_info){
		$all_result = "";//反饋給return用的沒啥大用！
		if($this->leg_cdb_info($db_info)){
			$this->$base_info["host"]=$db_info["host"];
			$this->$base_info["user"]=$db_info["user"];
			$this->$base_info["pwd"]=$db_info["pwd"];
			$this->$base_info["name"]=$db_info["name"];
			$test_result = $this->conncet_test($this->$base_info);
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
		
	}

	private function conncet_test($db_info){
		$dbc = mysqli_connect($base_info["host"],$base_info["user"],$base_info["pwd"],$base_info["name"])or $this->error("<p STYLE='color:red'>媽的智障！MySqli數據庫連接失敗！</p>");
		mysqli_close($dbc);
		return true;
	}
	//基礎CURD操作看對象方法名字相信您會明白！
	public function select(){

	}
	public function insert(){

	}
	public function update(){

	}
	public function del_row(){
	//刪除一個行(好像也有人叫他記錄)
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

}
$a = ["host"=>"acgbag.com","user"=>"HaradaShino","pwd"=>"wwww","name"=>"db"];
$test = new Qli($a);
var_dump($test);

/*
調試日誌 
--2016-11-18--

Notice: Undefined variable: base_info in D:\phpStudy\WWW\qli.php on line 22

Fatal error: Uncaught Error: Cannot access empty property in D:\phpStudy\WWW\qli.php:22 Stack trace: #0 D:\phpStudy\WWW\qli.php(77): Qli->__construct(Array) #1 {main} Next Error: Cannot access empty property in D:\phpStudy\WWW\qli.php:22 Stack trace: #0 D:\phpStudy\WWW\qli.php(77): Qli->__construct(Array) #1 {main} thrown in D:\phpStudy\WWW\qli.php on line 22--

不改錯了，先睡覺！= =
*/

?>