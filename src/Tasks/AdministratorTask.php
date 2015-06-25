<?php

namespace Argentum88\Phad\Tasks;

use Argentum88\Phad\Auth\Models\PhadAdministrators;
use Exception;
use Phalcon\CLI\Task;

class AdministratorTask extends Task
{
    public function newAction()
    {
        $params = $this->dispatcher->getParams();

        if (!isset($params['params'][0], $params['params'][1])) {

            echo "You did not specify the name or password.\n";
            return;
        }

        $name = $params['params'][0];
        $pass = $params['params'][1];

        $administrator = new PhadAdministrators();
        $administrator->name = $name;
        $administrator->password = $this->security->hash($pass);
        $administrator->created_at = $administrator->updated_at = date(DATE_ISO8601);

        try {

            $administrator->save();

        } catch (Exception $e) {

            $massage = $e->getMessage();
            echo "$massage\n";

            return;
        }

        echo "The administrator was created successfully.\n";
    }

    public function listAction()
    {
        try {

            $administrators = PhadAdministrators::find();

        } catch (Exception $e) {

            $massage = $e->getMessage();
            echo "$massage\n";

            return;
        }

        if (count($administrators) > 0) {

            echo "The list of administrators:\n";
        } else {

            echo "Administrators no.\n";
            return;
        }

        for ($i=0; $i<count($administrators); $i++) {

            $index = $i + 1;
            $name = $administrators[$i]->name;
            echo "$index. $name\n";
        }
    }

    public function deleteAction()
    {
        $params = $this->dispatcher->getParams();

        if (!isset($params['params'][0])) {

            echo "You did not specify the name.\n";
            return;
        }

        $name = $params['params'][0];

        try {

            $administrator = PhadAdministrators::findFirstByName($name);
            $administrator->delete();

        } catch (Exception $e) {

            $massage = $e->getMessage();
            echo "$massage\n";

            return;
        }

        echo "The administrator was deleted successfully.\n";
    }

    public function changePassAction()
    {
        $params = $this->dispatcher->getParams();

        if (!isset($params['params'][0], $params['params'][1])) {

            echo "You did not specify the name or password.\n";
            return;
        }

        $name = $params['params'][0];
        $pass = $params['params'][1];

        try {

            $administrator = PhadAdministrators::findFirstByName($name);
            $administrator->password = $this->security->hash($pass);
            $administrator->save();

        } catch (Exception $e) {

            $massage = $e->getMessage();
            echo "$massage\n";

            return;
        }

        echo "The password was changed successfully.\n";
    }
}
