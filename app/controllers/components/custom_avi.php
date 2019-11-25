<?php
class CustomAviComponent extends Object 
{
    function getCompanySqlById($company_ids, $modelName = 'Company', $fieldName = 'id', $operator = 'IN') 
	{
		$str = ":".$company_ids.":";
		$allIds = explode('::', $str);
		unset($str);
		$finalIds = array();
		foreach($allIds as $eachId)
		{
			if(!empty($eachId))
			{
				array_push($finalIds, $eachId);
			}
		}
		unset($allIds);
		unset($eachId);
		if($modelName == null)
		{
			return $finalIds;
		}
		else
		{
			$createSql = "{$modelName}.{$fieldName} {$operator} (";
			for($i = 0; $i < $total = count($finalIds); $i++)
			{
				if($i == ($total - 1))
				{
					$createSql .= "{$finalIds[$i]}";
				}
				else
				{
					$createSql .= "{$finalIds[$i]},";
				}
			}
			unset($total);
			unset($finalIds);
			$createSql .= ")";
			return $createSql;	
		}
    }
	
	function getAdminSqlByCompId($company_ids, $modelName = 'Admin', $fieldName = 'company_id', $operator = 'OR') 
	{
		$str = ":".$company_ids.":";
		$allIds = explode('::', $str);
		unset($str);
		$finalIds = array();
		foreach($allIds as $eachId)
		{
			if(!empty($eachId))
			{
				array_push($finalIds, $eachId);
			}
		}
		unset($allIds);
		unset($eachId);
		$createSql = "";
		for($i = 0; $i < $total = count($finalIds); $i++)
		{
			if($i == ($total - 1))
			{
				$createSql .= "{$modelName}.{$fieldName} like '%:{$finalIds[$i]}:%'";
			}
			else
			{
				$createSql .= "{$modelName}.{$fieldName} like '%:{$finalIds[$i]}:%' {$operator} ";
			}
		}
		unset($total);
		unset($finalIds);
		return $createSql;	
    }
}

?>
