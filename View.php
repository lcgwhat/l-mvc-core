<?php
/**
 * @author: liuchg
 *
 */

namespace lcgwhat\phpmvc;


use lcgwhat\phpmvc\exception\ViewFileNotFoundException;

class View
{
    /**
     * @var string $title
     */
    public  $title = ' ';
    public function renderContent($content)
    {
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $content, $layoutContent);
    }

    function renderView($view, $params=[]){
        $viewContent = $this->renderOnlyView($view,$params);

        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function renderOnlyView($view, $params)
    {

        $output = $this->renderPhpFile(Application::$ROOTPATH."/views/$view.php", $params);
        ob_start();
        $filename = Application::$ROOTPATH."/views/$view.php";
        if (!file_exists($filename)) {
            throw new ViewFileNotFoundException($filename.'不存在');
        }

        //require_once Application::$ROOTPATH."/views/$view.php";


        return $output;
    }

    public function layoutContent(){
        $layout = Application::$app->layout;
        $controller = Application::$app->getController();
        if (!is_null($controller)) {
            $layout = $controller->layout;
        }

        ob_start();
        require Application::$ROOTPATH."/views/layouts/$layout.php";
        return ob_get_clean();
    }

    /**
     * @param $_file_
     * @param array $_params_
     * @return false|string
     * @throws \Throwable
     */
    public function renderPhpFile($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }

            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}
