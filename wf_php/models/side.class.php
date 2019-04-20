<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:48:49
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-20 09:03:38
 */
class Side extends DBModel {
    function getSides($options) {
        $sql = "SELECT code, city, province, area from t_sides";
        $count = "SELECT count(*) from t_sides";
        if(!empty($options["search"])) {
            $search = $options["search"];
            $tj = " where area like '$search' or code like '$search%' or province like '$search%' or city like '$search'";
            $sql .= $tj;
            $count .= $tj;
        }
        $count = $this -> getone($count);
        $sql .= " limit {$options['dealPage']}";
        return array($this -> getrows($sql), 'totalCount' => $count);
    }
    function addSide($options) {
        $code = $options['code'];
        $area = $options['area'];
        $city = $options['city'];
        $province = $options['province'];
        $sql = "INSERT into t_sides(code, name) values('$code', '$name', '$name', '$name')";
        return $this -> exec($sql);
    }
    function recodeSide($options) {
        $code = $options['code'];
        $area = $options['area'];
        $city = $options['city'];
        $province = $options['province'];
        $sql = "UPDATE t_sides set code = '$code', city = '$city', province = '$province', area = '$area' where code = $code";
        return $this -> exec($sql);
    }
    function deleteSide($options) {
        $code = $options['code'];
        $sql = "DELETE from t_sides where code= $code";
        return $this -> exec($sql);
    }
    function searchCity($options) {
        $cName = $options['cName'];
        $sql = "SELECT code as cCode, name as cName from t_sides where name like '{$cName}%'";
        return $this -> getRow($sql);
    }
}