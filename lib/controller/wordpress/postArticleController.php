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
    );
    public function main ($config, $param) {
        $url = $config['host'] . '/wp-admin/post-new.php';
        $this->driver->get($url);


        $elm = $this->driver->findElement(
            WebDriverBy::cssSelector('input[name=post_title]')
        );
 
        $elm->sendKeys($param['title']);

        $this->driver->executeScript('document.getElementById("content_ifr").contentDocument.body.innerHTML = "'.$param['content'].'";'); 


        $button = $this->driver->findElement(
            WebDriverBy::cssSelector('.button-primary')
        );
        $this->getScreen();
        $button->click();

        $this->driver->wait(10, 500)->until(function ($driver) {
            $url = $driver->getCurrentURL();
            if (strpos($url, 'wp-admin/post.php') > 0) {
                error_log("Document is complete");
                return true;
            } else {
                return false;
            }
        });

        return true;
    }


}
