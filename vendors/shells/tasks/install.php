<?php
class InstallTask extends FlourShell
{
	var $methods = array(
		'install',
	);

	var $defaultFile = '/plugins/flour/config/sql/install.sql';

	function execute()
	{
		$this->install();
	}
	
	function install()
	{
		$file = array_shift($this->args);
		if(empty($file))
		{
			$file = ROOT.$this->defaultFile;
		}
		$this->out('');
		$this->out('   setting up database (creating tables) for flour...');
		$this->out('');
		$ds = $this->SqlFile->import($file);
		if($ds === false)
		{
			$this->out('   file not found or no statements found.');
			$this->out('');
			$this->out("   filename: $file");
			$this->out('');
			return -1;
		}
		$this->out('   done.');
		$this->out('');
		$this->out("   All tables have been populated into datasource: $ds");
		$this->out('');
	}

}
?>