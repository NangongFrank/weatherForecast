<?php
/**
 * @Author: name
 * @Date:   2019-03-20 16:24:32
 * @Last Modified by:   name
 * @Last Modified time: 2019-04-17 16:16:57
 */
class Admin extends DBModel {
    function getadmin($options) {
        $code = $options["code"];
        $pwd = $options["pwd"];
        $sql = "SELECT id as code, phone, name from t_user where phone = '$code' or name = '$code' and pwd = md5('$pwd') and id = 1";
        $up = "UPDATE t_user set last_login = now() where (phone = '$code' or name = '$code') and pwd = md5('$pwd')";
        $res = $this -> getrow($sql);
        $this -> exec($up);
        return $res;
    }
    function setAdminNewPwd($options) {
        $code = $options['code'];
        $oldPwd = $options["oldPwd"];
        $newPwd = $options["newPwd"];
        $phone = $options["phone"];
        $sql = "UPDATE t_user set pwd = md5('$newPwd') where id = $code and phone = '$phone' and pwd = md5('$oldPwd')";
        return $this -> exec($sql);
    }
}