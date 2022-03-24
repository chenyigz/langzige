<?php
/**
 * mysql导入类
 */
declare(strict_types=1);
namespace langzige\mysql;
class Import{
	protected $config = [];
	protected $content = '';
	protected $count = 0;

	public function __construct()
	{

		$config = Config('database')['connections']['mysql'];
		if($config){
			$this->config = array_merge($this->config,$config);
			$this->connection = $this->connections();
		}

	} 

	public function load($content)
	{
		
		$content = preg_replace('/\R*-- .*/', "", $content);
		$content = rtrim(trim(preg_replace('/\/\*(.+?)\*\//s', "", $content)),';');

		$search = [
			'EXISTS `',
			'TABLE `',
			'INTO `'
		];

		$replace = [
			 "EXISTS `".$this->config['prefix'],
			 "TABLE `".$this->config['prefix'],
			 "INTO `".$this->config['prefix']
		];

		$content = str_replace($search, $replace, $content);
		$this->count = substr_count($content,';');
		$this->content = $content;

		return $this;
	}

	public function exec()
	{

		function_exists('set_time_limit ') && set_time_limit(3600*24);

		$msg = '';
		$sqlcount = 0;

		$ret = $this->connection->multi_query($this->content);
		if($ret !== false) 
		{
			while (mysqli_more_results($this->connection)) 
			{
			    if (mysqli_next_result($this->connection) === false) 
			    {
			        break;
			    }
			    $sqlcount++;
			}

		} 

		$msg = mysqli_error($this->connection);

		$this->connection->close();

		return ['count'=>$this->count,'success'=>$sqlcount,'error'=>$this->count - $sqlcount,'msg'=>$msg];

	}

	public function connections()
	{
		return mysqli_connect($this->config['hostname'].':'.$this->config['hostport'], $this->config['username'], $this->config['password'], $this->config['database']);
	}


}
