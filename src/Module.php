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
    public $webControllersFolder = 'controllers';

    /**
     * @var string
     */
    public $consoleControllersFolder = 'commands';

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
     * @var bool
     */
    public $locateControllersByPath = true;

    /**
     * @var string
     */
    public $webControllersPath;

    /**
     * @var string
     */
    public $consoleControllersPath;

    /**
     * @return string
     */
    public function getControllerPath()
    {
        if ($this->locateControllersByPath) {
            if (\Yii::$app instanceof \yii\console\Application) {
                return $this->consoleControllersPath;
            } else {
                return $this->webControllersPath;
            }
        } else {
            return parent::getControllerPath();
        }
    }

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
        $localDir = dirname((new \ReflectionClass(static::class))->getFileName());
        $this->webControllersPath = "{$localDir}/{$this->webControllersFolder}";
        $this->consoleControllersPath = "{$localDir}/{$this->consoleControllersFolder}";

        //Проверяем наличие зависимостей
        $this->requireModules($app, $this->requiredModules);
        // если приложение консольное - используем консольные контроллеры
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = $this->baseNamespace . '\\commands';
        }

        //подключаем urlManager
        $path = $this->urlManagerPath ? \Yii::getAlias($this->urlManagerPath) : "{$localDir}/config/urlManager.php";
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
                throw new ServerErrorHttpException("Модуль {$name} указан в требованиях " . $idString . " но не обнаружен в приложении!");
            }
        }
    }
}