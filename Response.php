<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


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
