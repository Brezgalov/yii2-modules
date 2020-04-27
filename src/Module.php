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
     * @var string - базовое пространство имен. По умолчанию пытается получить текущее пространство имен класса
     */
    public $baseNamespace = '';

    /**
     * @var string
     */
    public $urlManagerPath;

    /**
     * Имя компонента приложения который используется для подключения к бд
     * @var string
     */
    public $dbConnection;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (empty($this->baseNamespace)) {
            $class = get_class($this);
            if (($pos = strrpos($class, '\\')) !== false) {
                $this->baseNamespace = substr($class, 0, $pos);
            }
        }
    }

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        //Проверяем наличие зависимостей
        $this->requireModules($app, $this->requiredModules);
        // если приложение консольное - используем консольные контроллеры
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = $this->baseNamespace . '\\commands';
        }
        //подключаем urlManager
        $path = $this->urlManagerPath ? \Yii::getAlias($this->urlManagerPath) : __DIR__ . '/config/urlManager.php';
        if (is_file($path)) {
            $app->getUrlManager()->addRules(require($path), false);
        }
    }

    /**
     * @param \yii\base\Application $app
     * @param array $names
     */
    public function requireModules(\yii\base\Application $app, array $names)
    {
        $idString = (@$this->id)? "модуля {$this->id} " : '';
        foreach ($names as $name) {
            if (!array_key_exists($name, $app->modules)) {
                throw new ServerErrorHttpException("Модуль $name указан в требованиях " . $idString . "но не обнаружен в приложении!");
            }
        }
    }
}