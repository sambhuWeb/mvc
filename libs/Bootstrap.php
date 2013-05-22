<?php

class Bootstrap {

  function __construct() {
    ob_start();
    Startup::setServerDetails();
    Session::init();//Find suitable place
    $this->permanentRedirectURL();

    //echo _SERVER_TYPE;exit();
    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = explode('/', $url);
    } else {
       //display index;
       require_once _APP_CONTROLLER.'index.php';
       $controller = new Index();
       $controller->index();
       return false;
    }

    //echo "<pre>".print_r($url, true)."</pre>";
    $controllerObj = $this->checkIfFileExist($url[0]);
    //echo "<pre>".print_r($controllerObj, true)."</pre>";
    

    //if $url[1] or $url[2] doesn't exist then assing null
    $methodName = isset($url[1])?$url[1]:null;
    $paramName = isset($url[2])?$url[2]:null;

    $controllerObj->loadModel($url[0]);//load model from constructor
    $this->checkIfOtherURLPartExist($controllerObj, $methodName, $paramName);    

    ob_end_flush();
  }

  /**
   *
   * Checks if the file exist;
   * IF FILE EXIST: Require FilePath and return the instanstiated contorller for e.g. new Help()
   * IF FILE DOESNT EXIST: Return the error page
   *
   * @param <type> $file  First part of Url which is file $url[0] (for e.g. help | index | login);
   *
   * Function made of following
   //Check if file exist   
    $file = _APP_CONTROLLER.$url[0]. '.php';
    if (file_exists($file)) {
      require $file;
    } else {
     $this->errorPage();
     return false;
    }
    //instanstiate new controller
    $controller = new $url[0];
   */
  private function checkIfFileExist($fileName){
    //Check if file exist
    $filePath = _APP_CONTROLLER.$fileName.'.php';
    if (file_exists($filePath)) {
      //echo "file exist<br />";
      require $filePath;
      //instanstiate and return new controller
      return new $fileName;// retrun new controller for e.g., new Help()
    } else {
      //echo "file doesn't exist<br />";
      require _APP_CONTROLLER."error.php";
      return new Error();      
    }
  }

  /**
   * Check if other part of the url exist and displays appropriate page accrodingly
   *
   * @param <type> $methodName  $url[1] www.domain.com/fileName/methodName
   * @param <type> $paramName   $url[2] www.domain.com/fileName/methodName/paramName
   *
   * FUNCTION OF:
     if (isset($url[2])) {
        if (method_exists($controller, $url[1])){//if method on the class exist
          //check if paramater value is set on the url
          //for e.g. www.mvc.lcl/help/other/10 (where $url[2] = 10)
          $controller->{$url[1]}($url[2]);//$controller->other(10)
        } else {
          $this->errorPage();
        }
      } else {
        //if it does call the method of the contoller for e.g.
        //if $url[1] = other
        //calls $controller->other()
        if (isset($url[1])) {
          if (method_exists($controller, $url[1])){//if method on the class exist
            $controller->{$url[1]}();
          } else {
            $this->errorPage();
          }
        } else {
          $controller->index();
        }
      }
   */
  private function checkIfOtherURLPartExist($controllerObj, $methodName, $paramName){
    //check if paramater value is set on the url [www.mvc.lcl/help/other/10 (where $url[2] = 10)]
    if (isset($paramName)) {      
      if (method_exists($controllerObj, $methodName)){//if methodName on the controler class exist
        $controllerObj->{$methodName}($paramName);//Help->other(10)
      } else {
        //print_r($controllerObj);
        $this->checkControllerObjErrClass($controllerObj);
      }
    } else {      
      //check if methodName provided in url exist [www.mvc.lcl/help/other (here methodName or $url[1] = other]
      if (isset($methodName)) {
        if (method_exists($controllerObj, $methodName)){//$controllerObj = help; $methodName = other
          $controllerObj->{$methodName}();//Help->other()
        } else {          
          //print_r($controllerObj);
          $this->checkControllerObjErrClass($controllerObj);
        }
      } else {
        $controllerObj->index();
      }
    }    
  }

  /*
   * This function checks if
   * $controllerObj is instance of Error class.
   * IF TRUE: then there is no need to require: _APP_CONTROLLER."error.php" as it is already called in ELSE section of checkIfFileExist() FUNCTION
   * IF FALSE: then create an object of Error class and pass it to the $this->errorPage function;
   */
  private function checkControllerObjErrClass($controllerObj){
    $errControllerObj;
    if ($controllerObj instanceof Error) {
      //Just for clarity
      $errControllerObj = $controllerObj;

      // $controllerObj is already error object
      $this->errorPage($errControllerObj);
    } else {
      require _APP_CONTROLLER."error.php";
      $errControllerObj = new Error();
      $this->errorPage($errControllerObj);
    }
  }

  private function errorPage($errorControllerObj){
    //require _APP_CONTROLLER."error.php";
    //$controller = new Error();
    $errorControllerObj->index();
    return false;
  }

  /**
   * Permanent redirect domain to www [301 Redirect]
   */
  private function permanentRedirectURL() {
    if (_SERVER_TYPE == "local") {
      if (strpos($_SERVER['HTTP_HOST'], "www") !== 0) {
        header("HTTP/1.1 301 Moved Parmanently");
        header("Location: http://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      }
    } else if (_SERVER_TYPE == "production") {
      if (strpos($_SERVER['HTTP_HOST'], "www") !== 0) {
        header("HTTP/1.1 301 Moved Parmanently");
        header("Location: http://www.".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
      }
    }
  }

}
