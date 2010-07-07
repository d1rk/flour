<?php
/**
 * Command-line utility to check for todo's in CakePHP application files.
 *
 * This utility will read all relevant files inside a CakePHP application and scan for '@todo's (as found in DocBlocks).
 *
 * @author 		Michael Hüneburg <mail@froschlaich.com>
 * @copyright 	Copyright 2009, Michael Hüneburg
 * @package 	vendors
 * @subpackage	vendors.shells
 * @license 	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', array('Folder', 'File'));
/**
 * Todo is a command-line utility to check for todo's in files.
 *
 * @package       vendors
 * @subpackage    vendors.shells
 */
class TodoTask extends FlourShell {
/**
 * Override initialize
 *
 * @access public
 */
	function execute() {
		$path    = '';
		$files   = array();
		$stat    = array('todos' => 0, 'files' => 0);
		
		$this->out('Cake Todo Shell');
		$this->hr();
		
		if(isset($this->params['path']) && !empty($this->params['path'])) {
			$path = DS . $this->params['path'];
		}
		// Read Folder(s).
		$fullPath = $this->params['working'] . $path;

		if(file_exists($fullPath)) {
			$folder = new Folder($fullPath);
			$files  = $folder->findRecursive('.+\.(php|ctp)');
		} else {
			$this->out(sprintf(__('Invalid Folder: %s', true), $path));
		}
		
		// Exit here if no files were found.
		if(empty($files)) {
			$this->out(__('No files in the specified directory(s).', true));
			return;
		}
		
		// Check files for todos.
		foreach($files as $filename) {
			$simplePath = str_replace($this->params['working'], '', $filename);
			$lines = file($filename);
			$out = array();
			
			// Check for todos line by line.
			foreach($lines as $no => $line) {
				if(preg_match('/ '."@".'todo([^\*\n]*)/i', $line, $matches) == 1) {
					$out[($no+1)] = trim($matches[1]);
				}
			}
			
			if(!empty($out)) {
				$this->out(sprintf(__('TODO in %s:', true), 'APP'.$simplePath));
				$stat['files']++;
				
				foreach($out as $line => $msg) {
					if(!empty($msg)) {
						$this->out('- ' . sprintf(__('Line %u: %s', true), $line, $msg));
					} else {
						$this->out('- ' . sprintf(__('Line %u', true), $line));
					}
					$stat['todos']++;
				}
				
				$this->hr();
			}
		}
		
		// Print statistics.
		$this->out(sprintf(__('Found %u todo(s) in %u file(s).', true), $stat['todos'], $stat['files']));
		
		if($stat['todos'] == 0) {
			$this->out(__('Lucky you.', true));
		}
	}
/**
 * Displays help contents
 *
 * @access public
 */
	function help() {
		$this->out("The Todo Shell scans your application for ".'@'."todo comments and displays them.");
		$this->hr();
		$this->out("Usage: cake flour todo <command> <arg1> <arg2>...");
		$this->hr();
		$this->out('Params:');
		$this->out("\n\t-path <dir>\n\t\tpath <dir> relative to your app to read and scan for todos.\n\t\tdefault path: /");
		$this->out('Commands:');
		$this->out("\n\ttodo help\n\t\tshows this help message.");
		$this->out("");
		$this->_stop();
	}
}
?>