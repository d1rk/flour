<?php
App::import('Model', 'ConnectionManager', false);
class DatabaseShell extends Shell
{

	public $folder = null;
	public $all = false;
	public $quiet = false;
	public $DbConfig = array();

/**
 * Starts up the the Shell
 * allows for checking and configuring prior to command or main execution
 * can be overriden in subclasses
 *
 * @access public
 */
	public function startup() {
		if (isset($this->params['folder'])) {
			if (substr($this->params['folder'], 0, 1) === DS) {
				$this->folder = $this->params['folder'];
			} else {
				$this->folder = APP . $this->params['folder'];
			}
			if (substr($this->folder, -1) !== DS) {
				$this->folder .= DS;
			}
		} else {
			$this->folder = CONFIGS . 'DBBackups' . DS;
		}
		$this->all = isset($this->params['all']);
		$this->quiet = isset($this->params['quiet']);
		$this->_welcome();
		if (!$this->_loadDbConfig()) {
			exit();
		}
	}

	public function main() {
		$this->out('Interactive Database Backup Shell');
		$this->hr();
		$this->out('[B]ackup');
		$this->out('[R]estore');
		$this->out('[H]elp');
		$this->out('[Q]uit');

		$classToBake = strtoupper($this->in(__('What would you like to do?', true), array('B', 'R', 'H', 'Q')));
		switch ($classToBake) {
			case 'B':
				$this->backup();
				break;
			case 'R':
				$this->restore();
				break;
			case 'H':
				$this->help();
				break;
			case 'Q':
				exit(0);
				break;
			default:
				$this->out(__('You have made an invalid selection. Please choose an action by entering B or R.', true));
		}
		$this->hr();
		$this->main();
	}

	public function backup() {
		$this->_clear();
		$this->out('Backup Task');
		$this->hr();
		$i = 1;
		foreach ($this->DbConfig as $config => $conn) {
			$configs[$i] = $config;
			$options[] = $i;
			$this->out('[' . $i . '] ' . $config);
		}
		if ($this->all || $this->quiet) {
			$selected = 'A';
		} else {
			$this->out('[A] All');
			$this->out('[Q] Quit');
			$options[] = 'A';
			$options[] = 'Q';
			$selected = strtoupper($this->in(__('Which configuration would you like to back up?', true), $options));
		}
		if ($selected !== 'Q') {
			if ($selected === 'A') {
				foreach ($configs as $config) {
					$this->out('Current Config: ' . $config);
					$this->_backup($config);
				}
			} else {
				$this->_backup($configs[(int)$selected]);
			}
			$this->out('Backup complete');
			sleep(2);
		}
		$this->_clear();
	}

	protected function _backup($config) {
		if (!class_exists('Folder')) {
			require LIBS . 'folder.php';
		}
		if (!class_exists('File')) {
			require LIBS . 'file.php';
		}
		$folder = new Folder($this->folder	. $config, true, 0777);
		$files = $folder->read(true, true);
		$files = $files[1];
		$version = -1;
		foreach ($files as $file) {
			$file = explode('_', $file);
			if (count($file) === 2) {
				$version = max($version, (int)$file[0]);
			}
		}
		$version++;
		$conn = $this->DbConfig[$config];
		$file = new File($this->folder . $config . DS . $version . '_' . time() . '.sql', true, 0777);
		foreach ($conn->listSources() as $table) {
			$describe = $conn->describe($table);
			$create = $conn->query("SHOW CREATE TABLE `" . $table . "`");
			$create = $create[0][0]['Create Table']; // Better way?
			$file->append("DROP TABLE IF EXISTS `" . $table . "`;\r\n" . $create . ";\r\n\r\n");
			$i = 0;
			foreach ($conn->query("SELECT * FROM `" . $table . "`") as $row) {
				$row = $row[$table];
				if ($i === 10) {
					$out = substr($out, 0, -3);
					$out .= ";\r\n\r\n";
					$file->append($out);
					$i = 0;
				}
				if ($i === 0) {
					$fields = array_keys($row);
					$out = "INSERT INTO `" . $table . "` (`" . implode('`, `', $fields) . "`) VALUES\r\n";
				}
				foreach ($row as $key => $val) {
					$row[$key] = $conn->value($val, $describe[$key]['type']);
				}
				$out .= "  (" . implode(", ", $row) . "),\r\n";
				$i++;
			}
			if ($i !== 0) {
				$out = substr($out, 0, -3);
				$out .= ";\r\n\r\n";
				$file->append($out);
			}
		}
	}

	public function restore() {
		$this->_clear();
		$this->out('Restore Task');
		$this->hr();
		$i = 1;
		foreach ($this->DbConfig as $config => $conn) {
			$configs[$i] = $config;
			$options[] = $i;
			$this->out('[' . $i . '] ' . $config);
		}
		if ($this->all || $this->quiet) {
			$selected = 'A';
		} else {
			$this->out('[A] All');
			$this->out('[Q] Quit');
			$options[] = 'A';
			$options[] = 'Q';
			$selected = strtoupper($this->in(__('Which configuration would you like to restore?', true), $options));
		}
		if ($selected !== 'Q') {
			if ($selected === 'A') {
				foreach ($configs as $config) {
					$this->out('Current Config: ' . $config);
					$this->_restore($config);
				}
			} else {
				$this->_restore($configs[(int)$selected]);
			}
			$this->out('Restoration complete');
			sleep(2);
		}
		$this->_clear();
	}

	protected function _restore($config) {
		$folder = new Folder($this->folder . $config, true, 0777);
		$files = $folder->read(true, true);
		$files = $files[1];
		$versions = array();
		foreach ($files as $file) {
			$file = explode('_', $file);
			if (count($file) === 2 && is_numeric($file[0]) && is_numeric(substr($file[1], 0, -4)) && substr($file[1], -4) === '.sql') {
				$versions[(int)$file[0]] = array(
					'date' => date('Y/m/d', substr($file[1], 0, -4)),
					'file' => $file[1]
				);
			}
		}
		if ($versions === array()) {
			$this->out('No backups to restore');
			sleep(5);
		} else {
			ksort($versions);
			foreach ($versions as $key => $version) {
				$this->out('[' . $key . '] Version ' . $key . ' created on ' . $version['date']);
			}
			if ($this->quiet) {
				$selected = (string)max(array_keys($versions));
			} else {
				$options = array_keys($versions);
				$options[] = 'Q';
				$selected = strtoupper($this->in('Which version would you like to restore?', $options));
			}
			if ($selected !== 'Q') {
				$conn = $this->DbConfig[$config];
				$file = new File($this->folder . $config . DS . $selected . '_' . $versions[(int)$selected]['file']);
				$contents = $file->read();
				$contents = explode(';', $contents);
				foreach ($contents as $query) {
					$query = trim($query);
					if (!empty($query)) {
						$conn->query($query);
					}
				}
			}
		}
	}

/**
 * Displays a header for the shell
 *
 * @access protected
 */
	public function _welcome() {
		$this->Dispatch->clear();
		$this->out();
		$this->out('Welcome to CakePHP v' . Configure::version() . ' Console Database Shell');
		$this->hr();
		$this->out('App : '. $this->params['app']);
		$this->out('Path: '. $this->params['working']);
		$this->hr();
	}

/**
 * Loads database file and constructs DATABASE_CONFIG class
 * makes $this->DbConfig available to subclasses
 *
 * @return bool
 * @access protected
 */
	public function _loadDbConfig() {
		$err = ini_get('error_reporting');
		ini_set('error_reporting', '0');
		foreach (ConnectionManager::enumConnectionObjects() as $name => $data) {
			$conn = ConnectionManager::getDataSource($name);
			if ($conn->connected) {
				$conn->cacheSources = false;
				$this->DbConfig[$name] = $conn;
			}
		}
		ini_set('error_reporting', $err);
		if ($this->DbConfig !== array()) {
			return true;
		} else {
			$this->err('No databases could be selected.');
			return false;
		}
	}

	public function _clear() {
		$this->Dispatch->clear();
	}

	public function out($string = null, $newline = true) {
		if (!$this->quiet) {
			return parent::out($string, $newline);
		} else {
			return false;
		}
	}

	public function in($prompt, $options = null, $default = null) {
		if (!$this->quiet) {
			return parent::in($prompt, $options, $default);
		} else {
			$this->err('Input required in quiet mode. You probably haven\'t specified whether to back up or restore');
			exit(1);
		}
	}

/**
 * Displays help contents
 *
 * @access public
 */
	function help() {
		$help = <<<TEXT
The Database Shell generates a SQL dump from your database
or restores the database from a previously taken SQL dump
---------------------------------------------------------------
Usage: cake database <command>
---------------------------------------------------------------
Params:
	-all
		Do selected actions on all available connections.

	-quiet
		Suppress all output. Sets -all as well. <command> must
		be set.

	-folder <folder>
		Changes folder backups are stored/looked for in.
		Default is app/config/DBBackup/<connection_name>/. Can
		be absolute or relative to app.

Commands:

	backup
		Backup databases.

	restore
		Restore database.
TEXT;
		$this->out($help);
		$this->_stop();
	}
}
?>