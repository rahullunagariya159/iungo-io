<?php 
		
		// $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
		$protocol;
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
	 	{ 
   			 $protocol = "https"; 
		}
		else
		{
    		$protocol = "http";
    	}

		$urlPath = $protocol."://".$_SERVER['HTTP_HOST']; 
			
		define("currentUrl", $urlPath);	
		

?>