<?php
class PrepareTask extends FlourShell
{

	var $verbose = false;

/**
 * initialize
 *
 * @return void
 */
	function initialize() {
		$this->path = ROOT.'/plugins/flour/templates';
		$this->target = ROOT;
	}

	function execute()
	{
		$this->prepare();
		//TOOD: set verbose to true on -v
	}
	
	function prepare()
	{
		$this->out('');
		$this->out('   copying files from templates to app.');
		$Folder =& new Folder($this->path);
		$files = $Folder->findRecursive(); //find all files in flour/templates
		foreach($files as $file)
		{
			$target = str_replace($this->path, $this->target, $file);
			$this->createFile($target, @file_get_contents($file));
		}
		$this->out('');
		$this->out('   done.');
		$this->out('');
	}

}
?>