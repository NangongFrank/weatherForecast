<?php

/**
 * @Author: zhao mac
 * @Date:   2019-03-18 19:32:29
 * @Last Modified by:   frank_zhao
 * @Last Modified time: 2019-04-20 21:42:40
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
            if($res) {
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
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function setUserSide($model, $options) {
        if(!empty($options['id']) && !empty($options['code'])) {
            $res = $model -> setUserSide($options['id'], $options['code']);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function setSideTemp($model, $options) {
        if(!empty($options['temp']) && !empty($options['id'])) {
            $res = $model -> updateTemp($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function resetUserPwd($model, $options) {
        if(!empty($options['pwd']) && !empty($options['name']) && !empty($options['phone'])) {
            $res = $model -> updateUserPwd($options);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
    function rmUserSide($model, $options) {
        if(!empty($options['id'])) {
            $res = $model -> deleteUserSide($options['id']);
            $this -> echofunc([$res]);
        } else {
            $this -> echofunc();
        }
    }
}