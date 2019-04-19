<?php

/**
 * @Author: zhao mac
 * @Date:   2019-03-18 19:32:29
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-19 19:38:58
 */
class User extends DBModel {
    function getUsers($options) {
        $sql = "SELECT id, phone, name, last_login as time from t_user";
        $count = "SELECT count(*) from t_user";
        if(!empty($options["search"])) {
            $search = $options["search"];
            $tj = " where name like '$search%' or phone like '$search%' and id <> 1";
        } else {
            $tj = " where id <> 1";
        }
        $sql .= $tj;
        $count .= $tj;
        $sql .= " limit {$options['dealPage']}";
        $count = $this -> getone($count);
        return array($this -> getrows($sql), 'totalCount' => $count);
    }
    function getActiveUsers($options) {
        $sql = "SELECT id, phone, name, last_login as time from t_user where id <> 1 order by last_login desc limit 10";
        return array($this -> getrows($sql));
    }
    function addUser($options) {
        $phone = $options['phone'];
        $name = $options['name'];
        $pwd = $options["pwd"];
        $sql = "INSERT into t_user(phone, name, pwd, last_login) values('$phone', '$name', md5('$pwd'), now())";
        return $this -> exec($sql);
    }
    function recodeUser($options) {
        $phone = $options['phone'];
        $name = $options['name'];
        $pwd = $options["pwd"];
        $id = $options['id'];
        $sql = "UPDATE t_user set phone = '$phone', name = '$name', pwd = md5('$pwd') where id = $id";
        return $this -> exec($sql);
    }
    function deleteUser($options) {
        $id = $options['id'];
        $sql = "DELETE from t_user where id = $id";
        return $this -> exec($sql);
    }
    function getUserSides($options) {
        $id = $options['id'];
        $sql = "SELECT name from t_user where id = '$id'";
        $user = $this -> getone($sql);
        $sql = "SELECT name as side, code from t_area where code in (select code from u_side where u_id = '$id')";
        $userSides = $this -> getRows($sql);
        return array('userName' => $user, 'sides' => $userSides);
    }
    function checkUserInfo($options) {
        $info = $options['info'];
        $pwd = $options['pwd'];
        $sql = "SELECT id as code, name as userName, phone from t_user where (name = '$info' or phone = '$info') and md5('$pwd')";
        return $this -> getrow($sql);
    }
    function getUserAllSide($options) {
        $id = $options['id'];
        $sql = "SELECT u.id as code, t.code as cCode, t.name as cName, u.temperature as tpr from u_side as u join t_area as t on u.code = t.code where u.u_id = '$id'";
        return $this -> getrows($sql);
    }
    function updateTpr($options) {
        $id = $options['usId'];
        $tpr = $options['tpr'];
        $sql = "UPDATE u_side set temperature = '$tpr' where id = '$id'";
        return $this -> exec($sql);
    }
}