<?php
class CMoneta
{
  var $m_sMySQLServer, $m_sMySQLDataBase, $m_sMySQLTableName, $m_sMySQLUserName, $m_sMySQLPassword, $m_hMySQLHandle;
  var $m_nRefreshCounter, $m_sPurchaseStatus, $m_sProviderData;
  
  function CMoneta($sMySQLServer, $sMySQLDataBase, $sMySQLTableName, $sMySQLUserName, $sMySQLPassword)
  {  
    $this->m_sMySQLServer    = $sMySQLServer;
	$this->m_sMySQLDataBase  = $sMySQLDataBase;
	$this->m_sMySQLTableName = $sMySQLTableName;
	$this->m_sMySQLUserName  = $sMySQLUserName;
	$this->m_sMySQLPassword  = $sMySQLPassword;

	$this->m_hMySQLHandle    = mysql_connect($sMySQLServer, $sMySQLUserName, $sMySQLPassword) or die ("Error: DB connect!");
	$result = mysql_select_db($sMySQLDataBase, $this->m_hMySQLHandle) or die ("Error: No database " . $sMySQLDataBase);
  }
  
  function AddMonetaPurchase($sProviderData)
  {
	$sConfirmationID = $this->MakeUniqueConfirmationID();
	$sProviderDataX  = str_replace("'", "''", $sProviderData);
	
	$sQuery = "insert into " . $this->m_sMySQLTableName . 
	          " (confirmationid, startdate, purchasestatus, refreshcounter, providerdata) values " . 
			  "('" . $sConfirmationID . "', NOW(), 'vobdelavi', 0, '" . $sProviderDataX . "')";
	
	$result = mysql_query($sQuery, $this->m_hMySQLHandle);
	
	if((!$result) || ($sConfirmationID==""))
	{
	  die ('Error:' . mysql_error() . " confirmationid=" . $sConfirmationID);
	}
	return $sConfirmationID;
  }
  
  function SetPurchaseStatus($sPurchaseStatus, $sConfirmationID)
  {
    $sQuery = "update " . $this->m_sMySQLTableName . 
	          " set purchasestatus='" . $sPurchaseStatus . "'" .
			  " where confirmationid='". $sConfirmationID ."'";
			  
	$result = mysql_query($sQuery, $this->m_hMySQLHandle);
	if(!$result)
	{
	  die("Error: Set status=". $sPurchaseStatus . " confirmationid=" . $sConfirmationID);
	}
  }
  
  function ConfirmPurchase($sPurchaseStatus, $sConfirmationID, $sConfirmationSignature, $sTarifficationError)
  {
    $sQuery = "update " . $this->m_sMySQLTableName . 
	          " set purchasestatus='" . $sPurchaseStatus . "', " .
			  " confirmationsignature='" . $sConfirmationSignature . "', " .
			  " tarifficationerror='" . $sTarifficationError . "', " .
			  " confirmdate=NOW() " .
			  " where confirmationid='". $sConfirmationID ."'";
			  
	$result = mysql_query($sQuery, $this->m_hMySQLHandle);
	if(!$result)
	{
	  die("Error: Confirm purchase=". $sPurchaseStatus . " confirmationid=" . $sConfirmationID);
	}
  }
  
  function AddRefreshCounter($sConfirmationID)
  {
    $sQuery = "update " . $this->m_sMySQLTableName . 
	          " set refreshcounter=refreshcounter+1 " .
			  "where confirmationid='" . $sConfirmationID . "'";
			  
	$result = mysql_query($sQuery, $this->m_hMySQLHandle);
	if(!$result)
	{
	  die("Error: Add refresh counter for confirmationid=" . $sConfirmationID);
	}
  }
  
  function FindConfirmationID($sConfirmationID)
  {
    $bReturn = false;
	
    $sQuery = "select refreshcounter, purchasestatus, providerdata from " . $this->m_sMySQLTableName . 
	          " where confirmationid='". $sConfirmationID ."'";
			  
    $result = mysql_query($sQuery, $this->m_hMySQLHandle);
	if(!$result)
	{
	  die("Error: Find confirmationid=". $sConfirmationID);
	}
	
	while($row = mysql_fetch_array($result))
    {
	    $bReturn = true;
		$this->m_nRefreshCounter = $row['refreshcounter'];
		$this->m_sPurchaseStatus = $row['purchasestatus'];
		$this->m_sProviderData   = $row['providerdata'];
    }
	
	return $bReturn;
  }
  
  function Get_RefreshCounter()
  {
    return intval($this->m_nRefreshCounter);
  }
  
  function Get_PurchaseStatus()
  {
    return $this->m_sPurchaseStatus;
  }
  
  function Get_ProviderData()
  {
    return $this->m_sProviderData;
  }
  
  function MakeUniqueConfirmationID()
  {
    return "".gmdate("dmYHis");
  }
  
  function Close()
  {
    if($this->m_hMySQLHandle)
	{
	  mysql_close($this->m_hMySQLHandle);
	  return true;
	}
	return false;
  }
}
?>