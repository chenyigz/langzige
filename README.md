# langzige

使用示例：

    $sqlstr = file_get_contents('./test.sql');

    $import = new Import();

    $import->load($sqlstr);
    $msg = $import->exec();
    var_dump($msg);

    打印返回：
    array(4) {
	  ["count"]=>
	  int(31334)
	  ["success"]=>
	  int(31334)
	  ["error"]=>
	  int(0)
	  ["msg"]=>
	  string(0) ""
	}