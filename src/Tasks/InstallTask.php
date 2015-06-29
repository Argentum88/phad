<?php

namespace Argentum88\Phad\Tasks;

use Phalcon\CLI\Task;

class InstallTask extends Task
{
    public function mainAction()
    {
        $projectRoot = __DIR__ . '/../../../../../';
        copy(__DIR__ . '/../Templates/phad-config.php', $projectRoot . 'phad-config.php');

        mkdir($projectRoot . 'Phad', 0755);
        copy(__DIR__ . '/../Templates/Phad.php', $projectRoot . 'Phad/Phad.php');

        mkdir($projectRoot . 'public/backend-assets', 0777);
    }
}
