<?php
/**
 * @author: liuchg
 *
 */

namespace app\core;


abstract class UserModel extends db\DbModel
{
    abstract public function getDisplayName():string;
}
