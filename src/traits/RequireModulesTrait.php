<?php

namespace app\modules\traits;

use yii\base\Application;
use yii\web\ServerErrorHttpException;

trait RequireModulesTrait
{
    public function requireModules(Application $app, array $names)
    {
        $idString = (@$this->id)? "модуля {$this->id} " : '';
        foreach ($names as $name) {
            if (!array_key_exists($name, $app->modules)) {
                throw new ServerErrorHttpException("Модуль $name указан в требованиях " . $idString . "но не обнаружен в приложении!");
            }
        }
    }
}