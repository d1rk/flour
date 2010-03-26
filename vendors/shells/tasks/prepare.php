<?php
class PrepareTask extends FlourShell
{
	var $methods = array('init');

/**
 * initialize
 *
 * @return void
 */
	function initialize() {
		$this->path = ROOT.'plugins/flour/templates';
	}


	function execute()
	{
		$method = array_shift($this->args);
		if(empty($method))
		{
			$method = 'help';
		}
		$this->prepare();
	}
	
	function prepare()
	{
		$this->out('');
		$this->out('   copying files from templates to app.');
		$this->out('');
		
		$Folder =& new Folder($this->path);
		$files = $Folder->cd();

		debug($files);

		$this->out('');
		$this->out('   done.');
		$this->out('');
	}

}
?>