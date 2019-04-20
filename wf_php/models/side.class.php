<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:48:49
 * @Last Modified by:   frank_zhao
 * @Last Modified time: 2019-04-20 17:33:14
 */
class Side extends DBModel {
    function getSides($options) {
        $sql = "SELECT t.code as code, t.city as city, t.province as province, t.area as area, h.side_order as `order`, h.id as id";
        $count = "SELECT count(*)";
        $formT = " from t_sides as t left join hotcitys as h on t.code = h.side_code";
        $sql .= $formT;
        $count .= $formT;
        if(!empty($options["search"])) {
            $search = $options["search"];
            $tj = " where area like '$search' or code like '$search%' or province like '$search%' or city like '$search'";
            $sql .= $tj;
            $count .= $tj;
        }
        if(!empty($options['hot'])) {
            $tj = " where h.side_order >= 1 order by h.side_order";
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
        $province = $options['province'];
        $city = $options['city'];
        $sql = "SELECT code, province, city, area from t_sides where province like '$province%' and city like '$city%'";
        return $this -> getRow($sql);
    }
    function getHotCitys() {
        $sql = "SELECT `code`,province,city,area from t_sides WHERE `code` in (SELECT `side_code` from hotcitys)  LIMIT 30";
        return $this -> getRows($sql);
    }
    function addHotCity($code) {
        $sql = "INSERT into hotcitys(side_code, side_order) values ($code, 1)";
        return $this -> exec($sql);
    }
    function rmHotCity($id) {
        $sql = "DELETE from hotcitys where id = $id";
        return $this -> exec($sql);
    }
    function chOrder($id, $order) {
        $sql = "UPDATE hotcitys set side_order = $order where id = $id";
        return $this -> exec($sql);
    }
}