<?php

class ReleaseShell extends Shell {
	public $tasks = array('Plugin');

	public function main() {
		do {
			$this->out('Interactive Release Shell');
			$this->hr();
			$this->out('[P]lugin');
			$this->out('[M]odule');
			$this->out('[T]heme');
			$this->out('Plugin [A]dd-on');
			$this->out('[Q]uit');

			$input = strtoupper($this->in('What do yolu wish to release?'));

			switch ($input) {
				case 'P':
					$this->Plugin->execute();
					break;
				case 'M':
					break;
				case 'T':
					break;
				case 'A':
					break;
				case 'Q':
					exit(0);
					break;
				default:
					$this->out('Invalid option');
					break;
			}
		} while($input != 'Q');
	}

}

