<?php
/**
 * @Author: name
 * @Date:   2019-04-17 14:49:20
 * @Last Modified by:   frank_zhao
 * @Last Modified time: 2019-04-20 17:51:54
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
        if(!empty($options['code']) && !empty($options['area']) && !empty($options['city']) && !empty($options['province'])) {
            $res = $model -> addSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function recodeRow($model, $options) {
        if(!empty($options['code']) && !empty($options['area']) && !empty($options['city']) && !empty($options['province'])) {
            $res = $model -> recodeSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function deleteRow($model, $options) {
        if(!empty($options['code'])) {
            $res = $model -> deleteSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    # using request weather
    function getCityCode($model, $options) {
        if(!empty($options['province']) && !empty($options['city'])) {
            $res = $model -> searchCity($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    # city search
    function getAboutCity($model, $options) {
        if(isset($options['search'])) {
            $options['dealPage'] = "30";
            $res = $model -> getSides($options);
            $this -> echofunc($res);
        } else {
            $this -> echofunc();
        }
    }
    function getHotCitys($model, $options) {
        # 默认最多30条
        $res = $model -> getHotCitys();
        $this -> echofunc([$res]);
    }
    function setHotCity($model, $options) {
        if(!empty($options['code'])) {
            $res = $model -> addHotCity($options['code']);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function rmHotCity($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> rmHotCity($options['id']);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function changeOrder($model, $options) {
        if(!empty($options['id']) && !empty($options['order'])) {
            $res = $model -> chOrder($options['id'], $options['order']);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
}