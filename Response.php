<?php
/**
 * @author: liuchg
 *
 */

namespace app\core;


class Response
{
    /**
     * @param int $statusCode
     */
    public function setCode($statusCode)
    {
        http_response_code($statusCode);
    }

    public function redirect(string $url)
    {
        $scriptUrl = Application::$app->request->getScriptUrl();
        header("Location: ".$scriptUrl.$url);
    }
}
