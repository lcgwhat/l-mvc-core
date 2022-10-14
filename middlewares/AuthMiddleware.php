<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\middlewares;


use lcgwhat\phpmvc\Application;
use lcgwhat\phpmvc\exception\ForbiddenException;

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
