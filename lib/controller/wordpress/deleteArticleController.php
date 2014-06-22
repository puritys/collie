<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';

class deleteArticleController extends collieBasicController {
    public $name = "Wordpress delete a article";
    public $formParam = array (
        "postId_getKey" => array(
            "label" => "The key to get Post Id",
            "type" => "input",
            "hint" => "If the previous controller already save post id, then you could type the key to get the post id",
        ),

    );
    public function main ($config, $param) {
        $url = $config['host'] . '/wp-admin/edit.php';
        $this->driver->get($url);

        $key = $param['postId_getKey'];
        $postId = $this->getData($key);
        $selectorPost = '#post-'.$postId;

        $elm = $this->driver->findElement(
            WebDriverBy::cssSelector($selectorPost)
        );

        $action = $this->driver->action();
        $action->moveToElement($elm);
        //usleep(200 * 1000);


        $this->driver->executeScript('var d = document.querySelector("'.$selectorPost.' .row-actions"); d.style.visibility="visible";');

        $selector = $selectorPost .'  .trash .submitdelete';
        $this->showLog("Delete the article id $postId", 1);
        $this->showLog("Selector is " . $selector, 1);

        $trashButton = $this->driver->findElement(
            WebDriverBy::cssSelector($selector)
        );
 
//        $this->getScreen();

        $trashButton->click();

        $this->driver->wait(10, 500)->until(function ($driver) {
            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin/edit.php') > 0) {
                return true;
            } else {
                return false;
            }
        });

        $this->deleteTrashPost($config, $param, $postId);

        return true;
    }

    public function deleteTrashPost($config, $param, $postId) {
        $url = $config['host'] . '/wp-admin/edit.php?post_status=trash&post_type=post';
        $this->driver->get($url);

        $this->driver->wait(10, 500)->until(function ($driver) {
            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin/edit.php?post_status=trash') > 0) {
                return true;
            } else {
                return false;
            }
        });

        $selectorPost = '#post-'.$postId;
        $this->driver->executeScript('var d = document.querySelector("'.$selectorPost.' .row-actions"); d.style.visibility="visible";');

        $selector = $selectorPost .'  .delete .submitdelete';
        $deleteButton = $this->driver->findElement(
            WebDriverBy::cssSelector($selector)
        );
 
        $this->getScreen();

        $deleteButton->click();

        $this->driver->wait(10, 500)->until(function ($driver) {
            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin/edit.php') > 0) {
                return true;
            } else {
                return false;
            }
        });

    }

}
