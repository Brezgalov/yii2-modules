<?php

namespace brezgalov\modules;

use yii\db\Connection;

trait GetModuleDbTrait
{
    /**
     * @param string $dbComponentName
     * @return Connection|null
     */
    public static function GetModuleDb($dbComponentName = 'dbConnection')
    {
        $module = \Yii::$app->controller->module;

        $componentName = isset($module->{$dbComponentName}) ? $module->{$dbComponentName} : 'db';

        return @\Yii::$app->{$componentName};
    }
}