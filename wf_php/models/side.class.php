<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:48:49
 * @Last Modified by:   name
 * @Last Modified time: 2019-04-17 17:53:57
 */
class Side extends DBModel {
    function getSides($options) {
        $sql = "SELECT id, code, name from t_area";
        $count = "SELECT count(*) from t_area";
        if(!empty($options["search"])) {
            $search = $options["search"];
            $tj = " where name like '$search%' or code like '$search%'";
            $sql .= $tj;
            $count .= $tj;
        }
        $count = $this -> getone($count);
        $sql .= " limit {$options['dealPage']}";
        return array($this -> getrows($sql), 'totalCount' => $count);
    }
    function addSide($options) {
        $code = $options['code'];
        $name = $options['name'];
        $sql = "INSERT into t_area(code, name) values('$code', '$name')";
        return $this -> exec($sql);
    }
    function recodeSide($options) {
        $code = $options['code'];
        $name = $options['name'];
        $id = $options['id'];
        $sql = "UPDATE t_area set code = '$code', name = '$name' where id = $id";
        return $this -> exec($sql);
    }
    function deleteSide($options) {
        $id = $options['id'];
        $sql = "DELETE from t_area where id = $id";
        return $this -> exec($sql);
    }
}