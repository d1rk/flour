<?php
/**
 * SQL Task
 *
 */
class SqlFileTask extends FlourShell {
	
/**
 * Execute the task
 *
 * @return void
 * @access public
 */
	function execute() {	}

/**
 * Import the file
 *
 * @return string dataset used or false, if error occured
 * @access public
 */
	function import($fileName, $datasource = 'default')
	{
		$ConnectionManager = &ConnectionManager::getInstance();
		$ds = (array_key_exists($datasource, $ConnectionManager->config)) ? $datasource : 'default'; //check, if this datasource exists
		$db = $ConnectionManager->getDataSource($ds);
		$statements = @file_get_contents($fileName);
		if(empty($statements))
		{
			return false;
		}
		$statements = explode(';', $statements);

		$prefix = (array_key_exists('prefix', $db->config)) ? $db->config['prefix'] : ''; //get current table-prefix

		$retVal = false;
		$this->ProgressBar->start(count($statements)-1); //remove one from last
		foreach ($statements as $statement)
		{
			if (trim($statement) != '')
			{
				$statement = str_replace('"', '`', $statement);
				$statement = str_replace("CREATE TABLE `{$datasource}_", "CREATE TABLE IF NOT EXISTS `$prefix", $statement);
				$statement = str_replace("CREATE TABLE IF NOT EXISTS `{$datasource}_", "CREATE TABLE IF NOT EXISTS `$prefix", $statement);
				$statement = str_replace("INSERT INTO `{$datasource}_", "INSERT INTO `$prefix", $statement);
				$statement = str_replace("INSERT IGNORE INTO `{$datasource}_", "INSERT IGNORE INTO `$prefix", $statement);

				$statement = String::insert(
					$statement,
					array(
						'UUID' => String::uuid(),
					)
				);

				$result = $db->query($statement);
				$this->ProgressBar->next();
				if($result !== false) $retVal = true;
			}
		}
		$this->datasource = $ds;
		return $ds;
	}
}
?>