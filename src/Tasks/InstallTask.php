<?php

namespace Argentum88\Phad\Tasks;

use Phalcon\CLI\Task;

class InstallTask extends Task
{
    public function mainAction()
    {
        $projectRoot = __DIR__ . '/../../../../../';

        if (!is_file($projectRoot . 'phad-config.php')) {

            copy(__DIR__ . '/../../templates/phad-config.php', $projectRoot . 'phad-config.php');
        }

        if (!is_dir($projectRoot . 'phad')) {

            mkdir($projectRoot . 'phad', 0755);
            copy(__DIR__ . '/../../templates/phad.php', $projectRoot . 'phad/phad.php');
        }

        if (!is_dir($projectRoot . 'public/backend-assets')) {

            mkdir($projectRoot . 'public/backend-assets');
            chmod($projectRoot . 'public/backend-assets', 0777);
            $this->recurseCopy(__DIR__ . '/../../templates/backend-assets', $projectRoot . 'public/backend-assets');
        }
    }

    protected function recurseCopy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
