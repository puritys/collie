<?php
require_once "caseSql.php";
//require_once 'arrowRunner.php';

class caseExe extends caseSql 
{
    public $dirPath = ""; //the descriptor tmp file  dir
    public $formPath = "";
    public function __construct($db, $dirPath = "")
    {
        parent::__construct($db);

        if ($dirPath) {
            $this->dirPath = $dirPath; 
        } else {
            $this->dirPath = PATH_DIR_RUN;
        }
//error_log("dirPath = " . $this->dirPath);
        if (!is_dir($this->dirPath))  {
            mkdir($this->dirPath);
        }

        $this->formPath = PATH_PROJECT . '/web';
//        $this->arrow = new arrowRunner();
    }

    public function getCase($args = "") 
    {/*{{{*/
        $res = $this->queryCase($args);
        if (!empty($args['isCount']) && $args['isCount'] == true) {
        } else {
            $this->transformData($res);
        }
        return $res;
    }/*}}}*/


    public function transformData(&$data) 
    {/*{{{*/
        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $data[$i]['urlDescriptor'] = URL_HOME . '/index.php?page=descriptor&action=read&id=' . $data[$i]['case_id'];
            $data[$i]['urlCaseRun'] = URL_HOME . '/index.php?page=case&action=run&id=' . $data[$i]['case_id'];
            $data[$i]['urlEdit'] = URL_HOME . '/index.php?page=caseForm&action=edit&id=' . $data[$i]['case_id'];
            $data[$i]['urlDelete'] = URL_HOME . '/index.php?page=caseRemove&id=' . $data[$i]['case_id'];

        }

    }/*}}}*/

    
    public function runAutomationCase($data, $config, $dirname, $reportDir = "", $readLog = true)
    {/*{{{*/
        if (empty($dirname)) { error_log("When try to run arrow, dirname is missing");return false;}
        if (empty($config)) { error_log("When try to run arrow, config is missing");return false;}
        if (empty($data)) { error_log("When try to run arrow, data is missing");return false;}

        $path = $this->dirPath . "/" . $dirname;
        if (!is_dir($path)) mkdir($path, 0755, true);

        if (!empty($reportDir)) {
            $dirname .= '/'. $reportDir;
            $path = $path .'/'. $reportDir;
            if (!is_dir($path)) mkdir($path);
        }
       

        $fileData = $path . "/tmp1.json";
        $dataC = '"config": {}';
        file_put_contents($fileData, $dataC);

        $descriptor = $this->createDescriptor($data);
        $file = $path . "/descriptor.json";
        file_put_contents($file, json_encode($descriptor));


        $configFilePath = $path . "/defaultConfig.json";
        $this->arrow->seleniumHost = $config['seleniumHost'];
        $this->arrow->saveConfig($configFilePath, $config);

        list($total, $passed, $failed) = $this->arrow->runCase($fileData, "", "noId", $readLog);
        return array(
            "dirname" => $dirname,
            "total" => $total,
            "passed" => $passed,
            "failed" => $failed,
        );
    }/*}}}*/

    //Support arrow(Node.js)
    public function createDescriptor($data) 
    {/*{{{*/
        $descriptor = $data['descriptor'];

        $descriptor = json_decode(html_entity_decode($descriptor), true);
        $result = array(
            "settings" => array("master"),
            "name" => $data['title'],
        );

        $cr = $descriptor['scenario'];
        $dataprovider = array();
        $n = count($cr);
        $scenario = array();


        for ($i = 0; $i < $n; $i++) {
            $it = $cr[$i];
            //$file = $this->formPath . '/' . $it['file'];
            //echo file_get_contents($file);
            $it['controller'] = preg_replace('/\.form/', '.js', $it['controller']); 
            $it['controller'] = PATH_PROJECT . '/controller/' . preg_replace('/^form\//', '', $it['controller']);
            if (!empty($it['type']) && $it['type'] == "test") {
                $it['test'] = $it['controller'];
                foreach ($it['params'] as $key => $value) {
                    if ($key == "test") {continue;} 
                    $it[$key] = $value;
                }
                unset($it['controller']);
                unset($it['params']);
            }
            $scenario[] = $it;
        }


        $tests = array (
            "group" => "int",
            "params" => array("scenario" => $scenario),
        );
        $result['dataprovider'] = array(
            "tests" => $tests,
        );
        return array($result , array("settings" => array( "environment:development")) );
    }/*}}}*/


}
