<?php

namespace brezgalov\modules;

use yii\db\Connection;

trait GetModuleDbTrait
{
    public static function getDefaultDbComponentName()
    {
        return 'db';
    }

    /**
     * @param string $dbComponentName
     * @return Connection|null
     */
    public static function getModuleDb($dbComponentName = 'dbConnection')
    {
        $module = @\Yii::$app->controller->module;

        $componentName = $module && isset($module->{$dbComponentName}) ? (
            $module->{$dbComponentName}
        ): (
            static::getDefaultDbComponentName()
        );

        return @\Yii::$app->{$componentName};
    }
}