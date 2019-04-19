<?php

/**
 * @Author: zhao mac
 * @Date:   2019-03-18 19:32:29
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-04-19 19:36:34
 */
class UserController extends BaseController  {
    function getRows($model, $options) {
        if(!empty($options['page']) && !empty($options['pageSize'])) {
            $page = $options['page'];
            $pageSize = $options['pageSize'];
            $options['dealPage'] = $this -> dealPage($page, $pageSize);
            $res = $model -> getUsers($options);
            $this -> echofunc($res);
        } else {
            $this -> echofunc();
        }
    }
    function getActiveRows($model, $options) {
        $res = $model -> getActiveUsers($options);
        $this -> echofunc($res);
    }
    # 用户注册 公共
    function addRow($model, $options) {
        if(!empty($options['pwd']) && !empty($options['name']) && !empty($options['phone'])) {
            $res = $model -> addUser($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function recodeRow($model, $options) {
        if(!empty($options['pwd']) && !empty($options['name']) && !empty($options['phone']) && !empty($options['id'])) {
            $res = $model -> recodeUser($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function deleteRow($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> deleteUser($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function getSides($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> getUserSides($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function userLogin($model, $options) {
        if(!empty($options['info']) && !empty($options['pwd'])) {
            $res = $model -> checkUserInfo($options);
            if(count($res) > 0) {
                $this -> echofunc([$res]);
            } else {
                $this -> echofunc();
            }
        } else {
            $this -> echofunc();
        }
    }
    function getUserSides($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> getUserAllSide($options);
            $this -> echofunc($res);
        } else {
            $this -> echofunc();
        }
    }
    function setUserSide($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> getUserAllSide($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function setSideTpr($model, $options) {
        if(!empyt($options['tpr']) && !empty($options['usId'])) {
            $res = $model -> updateTpr($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
}