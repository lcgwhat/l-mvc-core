<?php
/**
 * @author: liuchg
 *
 */

namespace app\core\form;

use app\core\Model;

class Form
{
    public static function begin($action, $method)
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new Form();
    }

    public static function end()
    {
        return '</form>';
    }

    /**
     * @param $model Model
     * @param $attribute
     */
    public function field($model, $attribute)
    {
        return new Field($model, $attribute);
    }
}
