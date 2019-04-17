<?php
header("Access-Control-Allow-Origin:*");
# header('Access-Control-Allow-Headers:x-requested-with,content-type');
require_once './test.config.php';
require_once "./base/DBModel.class.php";
require_once "./base/Factory.class.php";
require_once "./base/BaseController.class.php";
## 配置数据库模型实体类的连接配置属性
Factory :: setconfig($config);
## 配置请求参数，必须有c，f
# f标志着使用的controller类中方法的名称
# c标志着使用的类名，model的类名比controller的类名少一个controller
# 类名和方法名不区分大小写
# controller类中负责数据展示(response)

if(!empty($_REQUEST)) {
	$req = $_REQUEST;
    if(!empty($req["c"]) && !empty($req['f'])) {
        $classname = $req["c"];
        $funcName = $req["f"];
    } else {
        $res = array("msg" => "lost primary key words", "state" => 0);
        echo json_encode($res);
        die();
    }
    ## 获取传递的其他参数
    $options = array();
    foreach ($req as $key => $value) {
    	if($key == "c" || $key == "f") {
    		continue;
    	}
    	$options[$key] = $value;
    }
    if(count($options) == 0) {
    	$res = array("msg" => "not find using info value", "state" => 0);
        echo json_encode($res);
        die();
    }
    require_once "./models/" . $classname . ".class.php";
    require_once "./controllers/" . $classname . ".class.php";
    $model = Factory :: getmodel($classname);
    $controller = Factory :: getcontroller($classname . "Controller");
    $controller -> $funcName($model, $options);
} else {
    $res = array("msg" => "post and get as 'application/x-www-form-urlencoded' or 'multipart/form-data' to request", "state" => 0);
    echo json_encode($res);
}




