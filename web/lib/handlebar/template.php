<?php
require "lightncandy.inc";

class template {
    public $tmpDir;
    public $cache = false;
    public $baseDir = "";
    public function __construct($path, $cache = false, $baseDir = "") 
    {
        if (empty($path)) {
            $this->tmpDir = "";
        } else {
            $this->tmpDir = $path;
        }

        if (!is_dir($this->tmpDir)) {
            mkdir($this->tmpDir);
        }
        if ($cache) {
            $this->cache = $cache;
        }
        $this->baseDir = $baseDir;
    }

    public function render($template, $data) 
    {
        $html = file_get_contents($template);
        $dir = $this->tmpDir . '/' . dirname($template);
        $cache_file = $dir . '/'. basename($template, '.html') . '.php';

//error_log("cache_file = $cache_file");
//error_log("basdir  = " . dirname($template));
        if (!$this->cache || !is_file($cache_file)) {
            $php = LightnCandy::compile($html, Array('flags' => LightnCandy::FLAG_HANDLEBARSJS, "fileext" => "", "basedir" => dirname($template) . '/', 'blockhelpers' => Array('equal' => "template::help_equal") ));
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            if (!file_put_contents($cache_file, $php)) {
                error_log("Save lightncandy file failed.");
            }
            $exec = include($cache_file);

        } else {
            $exec = include($cache_file);
        }
        if ($exec) {
            return $exec($data);
        }
    }

    public function help_equal ($context, $args) {
        foreach ($args as $key => $val) {
            if ($context[$key] == $val) {
                return $context;
            } else {
                return null;
            }
        }
        return null;
    }

    public function renderLayout($layout) {
        $n = count($layout);
        for ($i = 0; $i < $n; $i++) {
            $temp = $layout[$i]['T'];
            $data = $layout[$i]['dataObject'];
            $grid = $layout[$i]['grid'];
            $html = $this->render(PATH_TEMP .'/'. $temp, $data);
            $html = <<<HTML
                <div class="yui3-$grid">
                    $html
                </div>
HTML;
        }
        return $html;
    }
}

