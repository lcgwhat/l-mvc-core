<?php
/**
 * @author: liuchg
 *
 */

namespace app\core\exception;


class NotFoundException extends \Exception
{
    protected $code = 404;
    protected $message = '页面不存在';
}
