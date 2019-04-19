<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:49:20
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-19 17:29:55
 */
class SideController extends BaseController  {
    function getRows($model, $options) {
        if(!empty($options['page']) && !empty($options['pageSize'])) {
            $page = $options['page'];
            $pageSize = $options['pageSize'];
            $options['dealPage'] = $this -> dealPage($page, $pageSize);
            $res = $model -> getSides($options);
            $this -> echofunc($res);
        } else {
            $this -> echofunc();
        }
    }
    function addRow($model, $options) {
        if(!empty($options['code']) && !empty($options['name'])) {
            $res = $model -> addSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function recodeRow($model, $options) {
        if(!empty($options['code']) && !empty($options['name']) && !empty($options['id'])) {
            $res = $model -> recodeSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function deleteRow($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> deleteSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    # request weather
    function getCityCode($model, $options) {

    }
    # city search
    function getAboutCity($model, $options) {

    }
    # random citys
    function getRandomCity($model, $options) {

    }
}