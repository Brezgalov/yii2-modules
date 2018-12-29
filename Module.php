<?php

namespace brezgalov\modules;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var array - массив модулей-зависимостей
     */
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

    /**
     * @param \yii\base\Application $app
     * @param array $names
     */
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