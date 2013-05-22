<?php
/**
 * Browser Capabilities
 * @return <type>
 */
function cur_browser(){
  // Create a new Browscap object (loads or creates the cache)
  $bc = new Browscap('tmp/cache/browsercap');
  // Get information about the current browser's user agent
  $current_browser = $bc->getBrowser();
  return $current_browser;
}
?>
