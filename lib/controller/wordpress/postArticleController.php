<?php
require_once PATH_CONTROLLER . '/basic/collieBasicController.php';

class postArticleController extends collieBasicController {
    public $name = "Wordpress post a article";
    public $formParam = array (
        "title" => array(
            "label" => "Article title",
            "type" => "input",
        ),

        "content" => array(
            "label" => "Article content",
            "type" => "input",
        ),
        "postId_savedKey" => array(
            "label" => "The key to save Post Id",
            "type" => "input",
            "hint" => "If you need save the post id, then you should type a key to save post id",
        ),

    );
    public function main ($config, $param) {
        $url = $config['host'] . '/wp-admin/post-new.php';
        $this->driver->get($url);
        $this->driver->wait(10, 500)->until(function ($driver) {

            $url = $driver->getCurrentURL();
            if (strpos($url, 'post-new.php') > 0) {
                return true;
            } else {
                return false;
            }
        });


        $elm = $this->driver->findElement(
            WebDriverBy::cssSelector('input[name=post_title]')
        );
 
        $elm->sendKeys($param['title']);

        $this->driver->findElement(
            WebDriverBy::cssSelector('#content-tmce')
        )->click();

        $this->driver->executeScript('document.getElementById("content_ifr").contentDocument.body.innerHTML = "'.$param['content'].'";'); 

//        $elm = $this->driver->findElement(
//            WebDriverBy::cssSelector('textarea[name=content]')
//        );

//        $elm->sendKeys($param['content']);

        usleep(1000 * 1000);
        $button = $this->driver->findElement(
            WebDriverBy::cssSelector('.button-primary')
        );

        $button->click();

        $this->driver->wait(20, 500)->until(function ($driver) {

            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin/post.php') > 0) {
                return true;
            } else {
                return false;
            }
        });

        $this->showLog("Post finish url = " . $url, 'debug');

        if (!empty($param['postId_savedKey'])) {

            $this->showLog("Save the post id and the key is " . $param['postId_savedKey'], 'debug');
            $url = $this->driver->getCurrentURL();
            $RegExp = '/post=([0-9]+)/';
            preg_match($RegExp, $url, $res);
            if (isset($res[1])) {
                $this->saveData($param['postId_savedKey'], $res[1]);
            }

        }

        return true;
    }


}
