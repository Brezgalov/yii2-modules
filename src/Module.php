<?php

namespace app\modules\base;

use app\modules\traits\RequireModulesTrait;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    use RequireModulesTrait;

    public $requiredModules = [];

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        //Проверяем наличие зависимостей
        $this->requireModules($app, $this->requiredModules);
        // если приложение консольное - используем консольные контроллеры
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = "app\modules\{$this->id}\commands";
        }
        //подключаем urlManager
        $path = \Yii::getAlias("@app/modules/{$this->id}/config/urlManager.php");
        if (is_file($path)) {
            $app->getUrlManager()->addRules(require($path), false);
        }
    }
}