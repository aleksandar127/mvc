<?php 

namespace Core;
use \App\Controllers\Controller;
use \App\Controllers\Register;
use \App\Controllers\Restricted;

class Router
{
    private $request;
    private $allowed_routes = array('Home','Register','Restricted');
    private $controllor,$method;
	
	public function __construct(Request $request)
	{
        $this->request=$request;
        $this->controller=$this->request->controller;
        $this->method=$this->request->method;
       
        $grantAccess=self::hasAccess( $this->controller,$this->method);    
        if(!$grantAccess){
           $this->controller=ACCESS_RESTRICTED;
           $this->method='index';
        }
        $controller=$this->pullInController(); 
       
        if (method_exists($controller, $this->method)) {
            $method=$this->method;
            $controller->{$method}();
        } 
        else {
           header("HTTP/1.0 404 Not Found");
           
        }
    }

    public function pullInController()
	{   $controllerName=$this->controller;
        if(in_array($controllerName,$this->allowed_routes)){
            
            $controllerName = '\\App\\Controllers\\'.$controllerName;
            
            return new $controllerName($this->controller,$this->method);
        }
        else
            return false;
	}

public static function redirect($location){
    if(!headers_sent()){
        header('Location: '.ROOT.$location);
        exit();
    }
    else{
        echo "<script type='text/javascript'>";
        echo "window.location.href='".ROOT.$location."'";
        echo "</script>";
        echo "<noscript>";
        echo "<meta http-equiv='refresh' content='0;url=".$location."'/>";
        echo "<noscript>";
        exit();
    }
}

public static function hasAccess($controllerName,$actionName='index'){

$acl_file=file_get_contents('./app/acl.json');
$acl=json_decode($acl_file,true);
$current_user_acls=["Guest"];
$grantAccess=false;
if(Session::exists(CURRENT_USER_SESSION_NAME)){
    $current_user_acls[]="LoggedIn";

foreach(currentUser()->acls() as $a){
    $current_user_acls[]=$a;
}
}
foreach($current_user_acls as $level){
    if(array_key_exists($level,$acl) && array_key_exists($controllerName,$acl[$level])){
        if(in_array($actionName,$acl[$level][$controllerName]) || in_array('*',$acl[$level][$controllerName])){
            $grantAccess=true;
            break;
        }
    }
}

foreach($current_user_acls as $level){
$denied=$acl[$level]['denied'];
if(!empty($denied) && array_key_exists($controllerName,$denied) && in_array($actionName,$denied[$controllerName])){
    $grantAccess=false;
    break;
}

}


return $grantAccess;

}

public static function getMenu($menu){
$menuArr=[];
$menuFile=file_get_contents(ROOT.'app/'.$menu.'.json');
$acl=json_decode($menuFile,true);
foreach($acl as $key=>$val){
    if(is_array($val)){
        $sub=[];
        foreach($val as $k=>$v){
            if($k=='separator' && !empty($sub)){
                $sub[$k]='';
                continue;
            }else if($finalVal=self::get_link($v)){
                $sub[$k]=$finalVal;
            }
        } 
        if(!empty($sub)){
            $menuArr[$key]=$sub;
        }
    }else{
        if($finalVal=self::get_link($val)){
            $menuArr[$key]=$finalVal;
        }
    }
}
return $menuArr;
}

public static function get_link($val){
    if(preg_match('/https?:\/\//',$val)){
        return $val;
    }else{
        $uArr=explode('/',$val);
        $controllerName=ucfirst($uArr[0]);
        $methodName=(isset($uArr[1]))?$uArr[1]:'';
        if(self::hasAccess($controllerName,$methodName)){
            return ROOT.$val;
        }
        return false;
    }
}




}