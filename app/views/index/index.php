<?php
echo "Message From Controller: ".$this->msg."<br />";
echo "SErver name: "._SERVER_NAME."<br>";
echo "Server type: "._SERVER_TYPE."<br>";
echo "oPDS: ". _OPDS."<br>";

echo "Output buffering is ".ini_get('output_buffering')."<br />";

echo "this is a index<br />";
//echo "<pre>".print_r(phpinfo(), true)."</pre>";
echo _CURRENT_DOMAIN . DS ."index<br />";

echo __FILE__;
echo "<br />";
echo strrchr(__FILE__, '.');
echo "<br />";
echo substr(strrchr(__FILE__, '.'), 1);
echo "<br /><br />";
echo "<pre>dirname(dirname(__FILE__)) . DS) <br /></pre>";
echo dirname(__FILE__)."<br />";
//echo dirname(dirname(__FILE__)).WFDS;

echo _OPDS;
echo _SERVER_TYPE;

echo '<pre>'. print_r(cur_browser(), 1).'</pre>';
?>