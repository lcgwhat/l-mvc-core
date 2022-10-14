<?php
/**
 * @author: liuchg
 *
 */

namespace app\core\middlewares;


use app\core\Application;
use app\core\exception\ForbiddenException;

class AuthMiddleware extends BaseMiddleWare
{
    public $actions = [];

    /**
     * AuthMiddleware constructor.
     * @param array $actions
     */
    public function __construct( $actions=[])
    {
        $this->actions = $actions;
    }

    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw  new ForbiddenException();
            }
        }
    }
}
