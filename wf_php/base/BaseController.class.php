<?php
/**
 * @Author: name
 * @Date:   2019-03-21 08:32:39
 * @Last Modified by:   name
 * @Last Modified time: 2019-04-17 16:20:55
 */
class BaseController {
    protected function echofunc($data = false) {
        if($data) {
            if(!empty($data['totalCount'])) {
                $data = array("data" => $data[0], "state" => 1, 'totalCount' => $data['totalCount']);
            } else {
                $data = array("data" => $data[0], "state" => 1);
            }
        } else {
           $data = array("msg" => "this request no response", "state" => 0);
        }
        echo json_encode($data);
    }
    protected function dealPage($page, $pageSize) {
        $page =  ($page - 1) * $pageSize;
        return $page . "," . $pageSize;
    }
}