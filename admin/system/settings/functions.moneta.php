<?php  
  // Functions_ResponseExpires()
  // Functions_UploadFile_GetContent($sFileName)
  // Functions_DownloadFile_PutFile($sFileName, $sFileExtension, $sFileData)
  // Functions_Request($sParameterName) 
  // Functions_RequestNumber($sParameterName, $nMin, $nMax, $nDefault)
  // Functions_RequestFloat($sParameterName, $fltMin, $fltMax, $fltDefault)
  // Functions_RequestString($sParameterName, $nMaxLen)
  // Functions_Validate_String($sData)
  // Functions_CInt($sNum)
  // Functions_CDbl($sNum)
  // Functions_ParseCityHouse($sAddress, &$sCity, &$sHouse)
  // Functions_GetParameterValue($sData, $sParameterName)

  //#######################################################################
  //##  Functions_ResponseExpires()
  //####################################################################### 
  function Functions_ResponseExpires()
  {
    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
    header ("Cache-Control: no-cache"); // HTTP/1.1 
    header ("Pragma: no-cache"); // HTTP/1.0 
    return 0;
  }
  
  //#######################################################################
  //##  Functions_UploadFile_GetContent($sFileName)
  //#######################################################################
  function Functions_UploadFile_GetContent($sFileName)
  {
    $data = "";
    $imgfile      = $_FILES[$sFileName]['tmp_name'];
    $imgfile_type = $_FILES[$sFileName]['type'];
    $imgfile_name = $_FILES[$sFileName]['name'];
    if (is_uploaded_file($imgfile))
    {
      $file = fopen($imgfile,'rb');
      $data = fread($file,filesize($imgfile));
      fclose($file);
    }
    return $data;
  }
  
  //#######################################################################
  //##  Functions_DownloadFile_PutFile($sFileName, $sFileData)
  //#######################################################################
  function Functions_DownloadFile_PutFile($sFileName, $sFileExtension, $sFileData)
  {
    $iFileLen = strlen($sFileData);    
    $datum = date("D, d M Y H:i:s")." GMT";
    header("Content-Type: application/$sFileExtension");
    header("Accept-Ranges: bytes");
    header("Last-Modified: $datum");    
    header("Expires: $datum");
    header("Content-Disposition: inline; filename=$sFileName.$sFileExtension");
    header("Pragma: no-cache");
    //header("Cache-Control: no-cache, must-revalidate");
    header("Cache-control: private");
    header("Content-Length: $iFileLen\n");    
    echo "$sFileData"; 
  }
  
  //##############################################################
  //## Functions_GetServerVariable($sVARIABLE)
  //##############################################################
  function Functions_GetServerVariable($sVARIABLE)
  {
    $sReturn = "";
    if(isset($_SERVER[$sVARIABLE]))
    {
      $sReturn = $_SERVER[$sVARIABLE];
    }
    return $sReturn;
  }

  //#######################################################################
  //##  Functions_Request($sParameterName)
  //#######################################################################
  function Functions_Request($sParameterName) 
  { 
    $sReturn = "";
    if (isset($_POST[$sParameterName])) 
    {
      $sReturn = $_POST[$sParameterName];
    }
    elseif (isset($_GET[$sParameterName]))
    {
      $sReturn = $_GET[$sParameterName];
    }
    return stripslashes($sReturn);
  } 
  
  //#######################################################################
  //##  Functions_RequestNumber($sParameterName, $nMin, $nMax, $nDefault)
  //#######################################################################
  function Functions_RequestNumber($sParameterName, $nMin, $nMax, $nDefault)
  {
    $nNum = Functions_Request($sParameterName);
	if($nNum=="")
	{
	  $nNum = "".$nDefault;
	}
    $nReturn = intval($nNum);
    if(!(($nReturn>=$nMin) && ($nReturn<=$nMax)))
    {
      $nReturn = $nDefault;
    }
    return $nReturn;
  }
  
  //#######################################################################
  //##  Functions_RequestFloat($sParameterName, $fltMin, $fltMax, $fltDefault)
  //#######################################################################
  function Functions_RequestFloat($sParameterName, $fltMin, $fltMax, $fltDefault)
  {
    $fltNum = Functions_Request($sParameterName);
    $fltReturn = floatval(str_replace(",",".","".$fltNum));
    if(!(($fltReturn>=$fltMin) && ($fltReturn<=$fltMax)))
    {
      $fltReturn = $fltDefault;
    }
    return $fltReturn;
  }

  //#######################################################################
  //##  Functions_RequestString($sParameterName, $nMaxLen)
  //#######################################################################
  function Functions_RequestString($sParameterName, $nMaxLen)
  {
    $sReturn = Functions_Request($sParameterName);
    if(strlen($sReturn)>$nMaxLen)
    {
      $sReturn = substr($sParameterName, 0, $nMaxLen);
    }
    return Functions_Validate_String($sReturn);
  }
  
  //#######################################################################
  //##  Functions_Validate_String($sData)
  //#######################################################################  
  function Functions_Validate_String($sData)
  {
    $sValidChars = array (0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 0, 0,
	                        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
            	            1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                        	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0,
                        	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0,
                        	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0,
                        	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                        	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                        	0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0,
                        	1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                        	0, 0, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0,
                        	1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $iLen = strlen($sData);    
    for ($i=0; $i<$iLen; $i++)    
    {      
      if ($sValidChars[ord($sData[$i])]==0)
      {
        $ta = $sData[$i];      
        $sData[$i] = chr(32);
      }
    }
    $sData = str_replace("'", "''", $sData);
    $sData = str_replace("%", " ",  $sData);
	$sData = str_replace("\\", " ", $sData);
    return $sData;
  }

  //#######################################################################
  //##  Functions_CInt($sNum)
  //#######################################################################   
  function Functions_CInt($sNum)
  {
    return intval($sNum);
  }
  
  //#######################################################################
  //##  Functions_CDbl($sNum)
  //#######################################################################  
  function Functions_CDbl($sNum)
  {
    return floatval(str_replace(",", ".", "".$sNum));
  }
  
  //#######################################################################
  //##  Functions_ParseCityHouse($sAddress, &$sCity, &$sHouse)
  //#######################################################################  
  function Functions_ParseCityHouse($sAddress, &$sCity, &$sHouse)
  {    
    $sData = "".$sAddress;
    $iLen = strlen($sData);
    $sHouse = "";
    $sCity = "";
    
    $a = 0;
    if($iLen>0)
    {
      for($i=$iLen-1;$i>-1;$i--)
      {
        if($a==0)
        {
          if($sData[$i]==" ")
          {
            $a=1;
          }
          else
          {
            $sHouse = $sData[$i].$sHouse;
          }
        }
        else
        {
          $sCity = $sData[$i].$sCity;
        }        
      }      
    }    
    return 0;
  }
  
  //#######################################################################
  //##  Functions_GetParameterValue($sData, $sParameterName)
  //#######################################################################  
  function Functions_GetParameterValue($sData, $sParameterName)
  {
    $sReturn = "";
    $nFrom = strpos($sData, $sParameterName."=");
  
    if(!($nFrom === false))
    {
      $nFrom = $nFrom + strlen($sParameterName) + 1;
	  $nTo = strpos($sData, "#", $nFrom);
	  if($nTo === false)
	  {
	    $nTo = strlen($sData);
	  }
	  if($nTo>$nFrom)
	  {
	    $sReturn = substr($sData, $nFrom, $nTo-$nFrom);
	  }
    }
 
    return $sReturn;
  }
?>