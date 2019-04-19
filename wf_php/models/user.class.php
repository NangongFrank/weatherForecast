<?php

/**
 * @Author: zhao mac
 * @Date:   2019-03-18 19:32:29
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-19 15:06:47
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
}