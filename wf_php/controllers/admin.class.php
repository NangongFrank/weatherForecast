<?php
/**
 * @Author: name
 * @Date:   2019-03-20 16:24:32
 * @Last Modified by:   name
 * @Last Modified time: 2019-04-17 16:20:27
 */
class AdminController extends BaseController {
	function adminlogin($model, $options) {
        if(!empty($options["code"]) && !empty($options['pwd'])){
           $res = $model -> getadmin($options);
           if($res) {
                $data = array("data" => $res, "state" => 1);
                echo json_encode($data);
           } else {
                $this -> echofunc();
           }
        } else {
            $this -> echofunc();
        }

    }
    function changeAdminPwd($model, $options) {
        if(!empty($options['code']) && !empty($options["oldPwd"]) && !empty($options["newPwd"]) && !empty($options['phone'])) {
            $res = $model -> setAdminNewPwd($options);
            $data = array("data" => $res, "state" => 1);
            echo json_encode($data);
        } else {
            $this -> echofunc();
        }

    }
}