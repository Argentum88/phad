<?php

namespace Argentum88\Phad\Tasks;

use Phalcon\CLI\Task;

class InstallTask extends Task
{
    public function mainAction()
    {
        $projectRoot = __DIR__ . '/../../../../../';
        copy(__DIR__ . '/../../templates/phad-config.php', $projectRoot . 'phad-config.php');

        mkdir($projectRoot . 'phad', 0755);
        copy(__DIR__ . '/../../templates/phad.php', $projectRoot . 'phad/phad.php');

        copy(__DIR__ . '/../../templates/backend-assets', $projectRoot . 'public');
    }
}
