<?php
class ResetDataShell extends Shell {
        var $uses = array('Installer.Release');

        function main() {
                $this->out('Reseting database');
                $this->Release->installData(true);
                $this->out('Done');
        }
}