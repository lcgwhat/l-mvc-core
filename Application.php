<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


use app\controllers\Controller;
use lcgwhat\phpmvc\db\Database;
use lcgwhat\phpmvc\db\DbModel;

class Application
{
    /**
     * @var Router $route
     */
    public  $route;

    /**
     * @var Request $request
     */
    public  $request;
    /**
     * @var Response $response
     */
    public $response;

    /**
     * @var Session $session
     */
    public $session;

    public static $ROOTPATH;

    /**
     * @var Application $app
     */
    public static $app;
    /**
     * @var $controller Controller
     */
    public $controller;
    /**
     * @var $db Database
     */
    public $db;
    public $layout = 'main';

    public function __construct($rootPath, $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOTPATH = $rootPath;
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db = new Database($config['db']);
        $this->route = new Router($this->request, $this->response);
        $this->view = new View();

        $primaryValue = $this->session->get('user');
        if ($primaryValue) {
            $primaryKey = $this->userClass::PrimaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }


    }

    public static function isGuest()
    {
        return !self::$app->user;
    }

    function run(){
        try {
            echo $this->route->resolve();
        }catch ( \Exception $e) {
            echo $this->view->renderView('_error', ['exception' => $e]);
        }

    }

    /**
     * @return Controller|null
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    /**
     * @var DbModel $user
     */
    public  $user;
    /**
     * @var string $userClass
     */
    public  $userClass;
}
