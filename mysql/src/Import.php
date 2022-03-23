<?php
declare(strict_types=1);
namespace langzige\mysql;
use think\Config;
class Import{
	protected $config = [];

	public function __construct($filename)
	{

		$config = Config::load('langzige');
		if($config){
			$this->config = array_merge($this->config,$config);
		}

		var_dump($this->config);

	} 


}
