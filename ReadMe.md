1. Создать папку **/modules/<Имя модуля>**
2. Создать **Module.php** внутри папки вида:
>     namespace app\modules\<Имя модуля>;
>     use yii\base\BootstrapInterface;
>   
>     class Module extends \app\modules\base\Module implements BootstrapInterface
>     {
>         /**
>          * @var string
>          */
>         public $id = '<Имя модуля>';
>     
>         /**
>          * {@inheritdoc}
>          */
>         public $controllerNamespace = 'app\modules\<Имя модуля>\controllers'; // пространство имен контроллеров
>     }
3. Создаем файл **/modules/<Имя модуля>/config/urlManager.php**, куда кладем роуты
4. Прописываем **<Имя модуля>** в **/config/modules** в виде:
> '<Имя модуля>'   => [
>     'class'     => 'app\modules\<Имя модуля>\Module', 
>     ],
5. Прописываем имя модуля в массив в файле **/config/bootstrap.php**