<?php
# =============================================================================
#     CLASS: Logging
#   PURPOSE: To provide a mechanism to dump logs
#    AUTHOR: Jarrar Jaffari (jarrar.jaffari@gmail.com)
# USE NOTES:
#     -) The logs go into /httpdocs/Logs
# =============================================================================
#

class Logging
{
    var $file_id;
    var $file_name;


    # =========================================================================
    #		    CONSTRUCTOR
    # =========================================================================
    
    function Logging($f) 
    {
	//$this->file_name = "/httpdocs/Logs/".$f;
	$this->file_id = fopen($f, "a+");
    }
      
    function Info($msg)
    {
      $str = "[" . date("Y/m/d h:i:s", mktime()) . "] [INFO]: " . $msg; 
      fwrite($this->file_id, $str . "\n");
    }

    function Debug($msg)
    {
      $str = "[" . date("Y/m/d h:i:s", mktime()) . "] [DEBUG]: " . $msg; 
      fwrite($this->file_id, $str . "\n");
    }

    function Error($msg)
    {
      $str = "[" . date("Y/m/d h:i:s", mktime()) . "] [ERROR]: " . $msg; 
      fwrite($this->file_id, $str . "\n");
    }

    function Close()
    {
	fclose($this->file_id);
    }
}
