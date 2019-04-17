<?php
/**
 * @Author: name
 * @Date:   2019-03-18 11:18:13
 * @Last Modified by:   name
 * @Last Modified time: 2019-03-30 09:42:22
 */
class DBModel {
	private static $link = null;
	function __construct($config) {
		$link = mysqli_connect($config['host'].':'.$config['port'], $config['user'], $config["pwd"], $config['dbName']);
		if ($link) {
			self :: $link = $link;
			self :: dbcharset($config['charset']);
		} else {
			die("mysql数据库连接失败");
		}
	}
	function dbcharset($charset = "utf8") {
		mysqli_set_charset(self :: $link, $charset);
	}
	function freeresult($result) {
		mysqli_free_result($result);
	}
	function geterr() {
		return mysqli_error(self :: $link);
	}
	function getrow($sql) {
		$result = mysqli_query(self :: $link, $sql);
		if ($result) {
			$res = mysqli_fetch_assoc($result);
			$this -> freeresult($result);
			return $res;
		} else {
			return array();
		}
	}
	function getrows($sql) {
		$result = mysqli_query(self :: $link, $sql);
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
			    $res[] = $row;
			}
			return $res;
			$this -> freeresult($result);
			return $res;
		} else {
			return array();
		}
	}
	function getone($sql) {
		$result = mysqli_query(self :: $link, $sql);
		if ($result) {
			$res = mysqli_fetch_row($result);
			$this -> freeresult($result);
			return $res[0];
		} else {
			return 0;
		}
	}
	function exec($sql) {
		mysqli_query(self :: $link, $sql);
		$sum = mysqli_affected_rows(self :: $link);
		if ($sum > 0) {
			return true;
		} else {
			return false;
		}
	}
}