<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc\exception;


class ViewFileNotFoundException extends \Exception
{
    protected $code = 500;
    protected $message = "视图文件不存在";

}
