<?php

class Bootstrap {
  private $_useragent;

  public function __construct() {
    ob_start();

    Startup::setServerDetails();
    Session::init();//Find suitable place
    $this->permanentRedirectURL();

    $this->_useragent=$_SERVER['HTTP_USER_AGENT'];

    if (isset($_GET['url'])) {
      $url = rtrim($_GET['url'], '/');
      $url = explode('/', $url);
    } else {
      //display index;
      require_once _APP_CONTROLLER.'index.php';
      $controller = new Index();

      if($this->checkIfMobile()) {// ! to test mobile version
        $controller->indexMobile();
      } else {
        $controller->index();
      }
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
   */
  private function checkIfFileExist($fileName) {
    //Check if file exist
    $filePath = _APP_CONTROLLER.$fileName.'.php';
    if (file_exists($filePath)) {
      require $filePath;
      //instanstiate and return new controller
      return new $fileName;// retrun new controller for e.g., new Help()
    } else {
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
   */
  private function checkIfOtherURLPartExist($controllerObj, $methodName, $paramName) {
    if (isset($paramName)) {
      if (method_exists($controllerObj, $methodName)) {//if methodName on the controler class exist
         if($this->checkIfMobile()) {//REMOME ! WHILE FINISH TESTING
           //Check if a methodMobile() Exist in a controller obj which is being passed for e.g. Help object
            if(method_exists($controllerObj, $methodName."Mobile")){
              $controllerObj->{$methodName."Mobile"}();//Help->otherMobile()
            } else { //If Mobile index() doesn't exist then display regular one
              $controllerObj->{$methodName}();//Help->other()
            }
          } else {
            $controllerObj->{$methodName}();//Help->other()
          }
      } else {
        $this->checkControllerObjErrClass($controllerObj);
      }
    } else {
      if (isset($methodName)) {//REMOME ! WHILE FINISH TESTING
        if (method_exists($controllerObj, $methodName)) {
          if($this->checkIfMobile()) {
            if(method_exists($controllerObj, $methodName."Mobile")){
              $controllerObj->{$methodName."Mobile"}();
            } else {
              $controllerObj->{$methodName}();
            }
          } else {
            $controllerObj->{$methodName}();
          }
        } else {
          $this->checkControllerObjErrClass($controllerObj);
        }
      } else {
        if($this->checkIfMobile()) {//REMOME ! WHILE FINISH TESTING
          if(method_exists($controllerObj, 'indexMobile')){
            $controllerObj->indexMobile();
          } else {
            $controllerObj->index();
          }
        } else {
          $controllerObj->index();
        }
      }
    }
  }

  /*
   * This function checks if
   * $controllerObj is instance of Error class.
   * IF TRUE: then there is no need to require: _APP_CONTROLLER."error.php" as it is already called in ELSE section of checkIfFileExist() FUNCTION
   * IF FALSE: then create an object of Error class and pass it to the $this->errorPage function;
  */
  private function checkControllerObjErrClass($controllerObj) {
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

  private function errorPage($errorControllerObj, $customMsg="") {
    $errorControllerObj->index($customMsg);
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

  /**
   * Check if it device is mobile
   */
  private function checkIfMobile(){
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$this->_useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($this->_useragent,0,4))) {
      return true;//false for test is as mobile on browser
    } else {
      return false;//true for test is as mobile on browser
    }
  }
}
