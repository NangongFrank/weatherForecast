<?php

/**
 * @Author: zhao mac
 * @Date:   2019-03-20 08:41:16
 * @Last Modified by:   zhao mac
 * @Last Modified time: 2019-03-20 20:03:19
 */
## 单例+工厂的结合模型
class Factory {
	private function __construct() {}
	private static $Models = array();
	private static $Controllers = array();
    private static $config = null;
	function getmodel($name) {
        if (!empty(self :: $Models[$name])) {
            return self :: $Models[$name];
        } else {
            $obj = new $name(self :: $config);
            self :: $Models[$name] = $obj;
            return $obj;
        }
    }
    function getcontroller($name) {
        if (!empty(self :: $Controllers[$name])) {
            return self :: $Controllers[$name];
        } else {
            $obj = new $name();
            self :: $Controllers[$name] = $obj;
            return $obj;
        }
    }
    function setconfig($config) {
        self :: $config = $config;
    }
}