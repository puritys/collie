<?php

class dataUtil
{

    static function convertCategory($data)
    {/*{{{*/
        $new = array();
        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $it = $data[$i];
            $a = array(
                "createTime" => $it['create_time'] . "<br />" ,
                "name" => $it['name'],
                "categoryId" => $it['category_id'],
                "parentId" => $it['parent_id'],
                "urlEdit" => "index.php?page=categoryForm&id=" . $it['category_id'],
                "urlDelete" => "index.php?page=categoryRemove&id=" . $it['category_id'],
            );
            $new[] = $a;
        }

        return $new;

    }/*}}}*/

    static function convertCateoryOption($cate, $parentId, $level, $selectId = "", &$res)
    {/*{{{*/
        $gap = "|-";
        for ($i = 1; $i < $level; $i++) {
            $gap .= "--";
        }
        $n = count($cate);
        for ($i = 0; $i < $n; $i++) {
            $on = false;
            if ($selectId && $cate[$i]['category_id'] == $selectId) $on = true;
            if (!$parentId && empty($cate[$i]['parent_id'])) {
                
                $res[] = array(
                    "categoryId" => $cate[$i]['category_id'],
                    "name" => $gap .$cate[$i]['name'],
                    "on" => $on,
                );
                self::convertCateoryOption($cate, $cate[$i]['category_id'], $level + 1, $selectId, $res);
            } else if (!empty($cate[$i]['parent_id']) && $parentId == $cate[$i]['parent_id']) {
                $res[] = array(
                    "categoryId" => $cate[$i]['category_id'],
                    "name" => $gap . " " .$cate[$i]['name'],
                    "on" => $on,
                );
                self::convertCateoryOption($cate, $cate[$i]['category_id'], $level + 1, $selectId, $res);


            }

        }

    }/*}}}*/

    static function convertReportGroup($data)
    {/*{{{*/
        $newData = array();
        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $it = $data[$i];
            $key = self::convertReportStatus($it);
            $new = array(
                "name" => $it['name'],
                //"on" => $on,
            );
            $new[$key] = true;
            $new['showReport'] = true;
            $newData[] = $new;
        }
        return $newData;

    }/*}}}*/

    static function configToHandlebar($data)
    {/*{{{*/
        $retval = array();
        foreach ($data as $key => $val) {
            if (preg_match('/^_/', $key)) continue;
            $retval[] = array(
                "key" => $key,
                "value" => $val,
            );
        }
        return $retval;
    }/*}}}*/

    static function matchCaseReport($cases, $reports)
    {/*{{{*/
        $n = sizeof($cases);
        for ($i = 0; $i < $n ; $i++) {
            foreach ($reports as $it) {
                if ($cases[$i]['case_id'] == $it['job_id']) {
                    $cases[$i]['report_id'] = $it['execute_id'];
                    break;
                }
            }
        }
        return $cases;
    }/*}}}*/


    static function convertPages($args) 
    {/*{{{*/
        $pages = array("pages" => array());
        $currentPage = $args['currentPage'];
        
        $qty = $args['quantity'];
        $pageSize = $args['pageSize'];
        $maxPage = ceil($qty/$pageSize);

        $pageNum = 10;
        $link = $args['link'];
        if (preg_match('/^\?/', $link)) {
            $link .= "?";
        } else {
            $link .= "&";
        }

        $i = ($args['currentPage'] > 5)? $args['currentPage'] - 5: 1;
        for ($j = 0; $i <= $maxPage && $j < $pageNum; $i++, $j++) {
            $on = false;
            if ($i == $currentPage) $on = true;
            $pages['pages'][] = array(
                "page" => $i,
                "link" => $link. 'currentPage='. $i,
                "on" => $on,
            );    
    
        }
        return $pages;
    }/*}}}*/

    static function convertReportStatus($report)
    {/*{{{*/
        if ($report['failed_case_num'] > 0 && $report['passed_case_num'] > 0) {
            return 'isPassSome';
        } else if ($report['failed_case_num'] > 0) {
            return 'isFail';
        } else if ($report['passed_case_num'] > 0) {
            return 'isPass';
        }
        return 'isWaiting';
    }/*}}}*/

    static function convertReportStatusByStatus($status)
    {/*{{{*/
        if ($status == 'some_failed') {
            return 'isPassSome';
        } else if ($status == 'failed') {
            return 'isFail';
        } else if ($status == 'passed') {
            return 'isPass';
        } else if ($status == 'running') {
            return 'isRunning';
        }
        return 'isWaiting';
    }/*}}}*/

}
