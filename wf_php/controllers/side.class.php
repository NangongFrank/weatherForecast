<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:49:20
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-19 19:47:26
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
        if(!empty($options['cName'])) {
            $res = $model -> searchCity($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    # city search
    function getAboutCity($model, $options) {
        if(!empty($options['cName'])) {
            $res = $model -> searchCitys($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    # random citys - hot citys
    function getRandomCity($model, $options) {

    }
}