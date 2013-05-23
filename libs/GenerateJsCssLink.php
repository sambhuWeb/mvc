<?php

class GenerateJsCssLink {

   /**
   * Option 1: $this->combineCSSFiles(array("bootstrap", "custom"));
   *  Combines all the custom css files passed as array from the parameter,
   *  Construct link and return For e.g. in above case it will be /loader/css/bootstrap|custom/
   *
   * Option 2: Use default if custom param not passed ($this->combineCSSFiles()
   * If parameter not provided then use default combined css link i.e. /loader/css/bootstrap|bootstrap-responsive|main-style/
   *
   * @param <type> $cssFiles Array consisting css file names without .css ext
   * @return <type> Combined linke of css file for e.g. /loader/css/bootstrap|main/
   */
  public static function combineCSSFiles($cssFiles = ""){
     $link = "";
      //If array not set || array not provided || if provided array is empty then display default CSS
      if (!isset($cssFiles) || !is_array($cssFiles) || empty($cssFiles)) {
        $link = "/loader/css/bootstrap|bootstrap-responsive|default/";//default combined CSS LInk
      } else {
        //Loop through array to construct combined CSS link
        $link = "/loader/css/";
        foreach ($cssFiles as $cssFile) {
          $link .= $cssFile.'|';
        }
        //e.g. /loader/css/bootstrap|custom|
        $link = substr_replace($link, "/", -1);//replace last char | with / char
      }
      return $link;
  }

  /**
   * Option 1: $this->combineJSFiles(array("bootstrap", "alert"));
   *  Combines all the custom css files passed as array from the parameter,
   *  Construct link and return For e.g. in above case it will be /loader/js/bootstrap|alert/
   *
   * Option 2: Use default if custom param not passed ($this->combineCSSFiles()
   * If parameter not provided then use default combined css link i.e.
   * /loader/js/jquery|bootstrap|bootstrap-collapse|bootstrap-modal|plusone|twitter-wg|jscycle|validator|main|homepage/
   *
   * @param <type> $cssFiles Array consisting js file names without .js ext
   * @return <type> Combined linke of js file for e.g. /loader/js/bootstrap|main/
   */
  public static function combineJSFiles($jsFiles = ""){
     $link = "";
      //If array not set || array not provided || if provided array is empty then display default JS
      if (!isset($jsFiles) || !is_array($jsFiles) || empty($jsFiles)) {
        $link = "/loader/js/jquery|bootstrap|bootstrap-collapse|bootstrap-modal/";//default combined JS LInk
      } else {
        //Loop through array to construct combined JS link
        $link = "/loader/js/";
        foreach ($jsFiles as $jsFile) {
          $link .= $jsFile.'|';
        }
        //e.g. /loader/js/bootstrap|custom|
        $link = substr_replace($link, "/", -1);//replace last char | with / char
      }

      return $link;
  }

}