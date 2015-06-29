<?php

namespace Argentum88\Phad\Tasks;

use Phalcon\CLI\Task;

class InstallTask extends Task
{
    public function mainAction()
    {
        $projectRoot = __DIR__ . '/../../../../../';
        copy(__DIR__ . '/../Templates/phad-config.php', $projectRoot . 'phad-configgg.php');
    }
}
