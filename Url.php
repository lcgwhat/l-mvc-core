<?php
/**
 * @author: liuchg
 *
 */

namespace app\core;


class Url
{
    public static function to($path, $params=[])
    {
        $scriptUrl = Application::$app->request->getScriptUrl();
        $cacheKey = "";
        foreach ($params as $key => $value) {
            if ($value !== null) {
                $cacheKey .= $key . '&';
            }
        }
        $path = trim($path,'/');

        $scriptUrl .= '/'.$path;
        if (!empty($params)) {
            $scriptUrl .= '?'.$cacheKey;
        }
        return $scriptUrl;
    }
}
