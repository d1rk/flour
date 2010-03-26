<?php
App::import('Core', array('Security', 'String'));
class FlourShell extends Shell
{
	var $tasks = array(
		'ProgressBar',
		'SqlFile',
		'Install',
		'Prepare',
	);

	var $methods = array(
		'help',
	);

	function main()
	{
		$method = array_shift($this->args);
		if(empty($method)) $method = 'help';
		if(in_array($method, $this->methods))
		{
			$this->$method();
		} else {
			$this->help();
		}
	}

	function help()
	{
		$this->out('');
		$this->out('  Adds flour to your cake.');
		$this->hr();
		$this->out('  available tasks: ');
		$this->out('');
		$this->out('  - install      installs all relevant tables');
		$this->out('  - prepare      copies needed files from flour/templates to app/');
		$this->out('  - help         prints the help you are looking at, right now.');
		$this->out('');
	}

}
?>