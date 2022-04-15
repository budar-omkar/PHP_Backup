<?
	function show_doctype_details($doc_id){
		$sql="select * from vendor_data_list where vendor_list_id='$doc_id' order by display_srno";
		$rs = exec_qry($sql);
		   $result = $rs['result'];
		   $x = 0;
		   ?><table class=swfb width=500> <tr class=swfb>
		   <td class=swfb> <center>Display Srno</td>
		   <td class=swfb> <center>Fields </td>
		   <td class=swfb> <center>Field Types</td>
		   <td class=swfb> <center>Display </td>
		   </tr><?
		   while ($myrow = mysqli_fetch_array($result)) 
		   {
				$srno = $myrow['srno'];
				$vendor_field = $myrow['vendor_field'];
				$field_type = $myrow['field_type'];
				$display_srno = $myrow['display_srno'];
				$display = $myrow['display'];
				?>
				<tr bgcolor="white" class=swfb>
					<td class=swfb><center><?=$display_srno;?></td>
					<td class=swfb> &nbsp; <?=$vendor_field;?></td>
					<td class=swfb> &nbsp; <?=$field_type;?></td>
					<td class=swfb><center><?=$display;?></td>
				</tr>
				<?
		   }
		?></table><?
	}
	
	function docid_2details($id){
		 $sql="select b.org_name,i.doc_name,d.div_name,c.vendors_name,f.state_name,concat(b.org_name,' / ',d.div_name,' / ',c.vendors_name,' / ',f.state_name,' / ',h.city_name,'->',g.site_name) as details,  h.city_name,g.site_name,  a.org_id,a.div_id,a.vendor_id,a.loc_id,a.site_id,a.vendor_list_id   from vendor_data a,org b ,vendors c,divisions_master d,location_master e,state_master f,site g,city_master h,doc_type_master i  where a.org_id=b.org_id   and a.vendor_id=c.vendor_id  and a.div_id=d.div_id  and a.loc_id=e.loc_id  and e.state_id=f.state_id  and a.site_id=g.site_id  and g.city_id=h.city_id  and a.vendor_list_id=i.doc_id  and a.vendor_data_id='$id'";
		 return get_val($sql,"details");
	}

	function show_details($org_id,$div_id,$vendor_id,$location_id,$site_id)
	{
		$org_name = $div_name = $vendor_name = $location_name = $site_name = "";
		if($org_id){
			$org_name = get_val("select org_name from org where org_id='$org_id'", "org_name");
		}
		if($div_id){
			$div_name = get_val("select div_name from divisions_master where org_id='$org_id' and div_id='$div_id'", "div_name");
		}
		if($vendor_id){
			$vendor_name = get_val("select vendors_name from vendors where vendor_id='$vendor_id'", "vendors_name");
		}
		if($location_id){
			$location_name = get_val("select b.state_name from location_master a, state_master b where a.loc_id='$location_id' and a.state_id=b.state_id", "state_name");
		}
		if($site_id){
			$site_name = get_val("select CONCAT(b.city_name,'-->',a.site_name)as site_name from site a,city_master b where a.city_id=b.city_id and site_id='$site_id'", "site_name");
		}
		
		?><label for="inputEmail3" class="p-2 mb-2 bg-primary text-white"><b>Organisation :</b> <?= $org_name; ?>  
		<? 
		if($div_id)
		{
			?>
			<b> / </b> 
			<?	echo  $div_name;  
		}
			if($vendor_id)
			{
				?><b> / </b> <? echo $vendor_name; 
			}	
				if($location_id)
				{
					?><b> / </b> <? echo $location_name; 
				}
					if($site_id)
					{
							?><b> / </b> <?= $site_name; 
					}	
			?></label><?

	}


	function users_id2details($usercode)
	{
		$sql="select concat(b.org_name,' / ',c.div_name,' / ',d.vendors_name,' / ',f.state_name,' / ',h.city_name,'->',g.site_name) as details,  a.vuser_id,a.vu_fname,a.vu_lname,b.org_name,c.div_name,d.vendors_name,f.state_name,g.site_name,h.city_name,a.contact_no,a.email,a.pvgs,a.vpassword,a.iscurrent,a.created_by,a.vloginname from login_master a,org b,divisions_master c,vendors d,location_master e,state_master f,site g,city_master h where a.site_id=g.site_id and g.city_id=h.city_id and e.state_id=f.state_id and a.loc_id=e.loc_id and a.vendor_id=d.vendor_id and a.div_id=c.div_id and a.client_id=b.org_id and vuser_id='$usercode'";
		  $rs = exec_qry($sql);
		   $result = $rs['result'];
		   $x = 0;
		   while ($myrow = mysqli_fetch_array($result)) 
		   {
				$data['details'] = $myrow['details'];
				$data['vuser_id'] = $myrow['vuser_id'];
				$data['vu_fname'] = $myrow['vu_fname'];
				$data['vu_lname'] = $myrow['vu_lname'];
				$data['org_name'] = $myrow['org_name'];
				$data['div_name'] = $myrow['div_name'];
				$data['vendors_name'] = $myrow['vendors_name'];
				$data['state_name'] = $myrow['state_name'];
				$data['site_name'] = $myrow['site_name'];
				$data['city_name'] = $myrow['city_name'];
				$data['contact_no'] = $myrow['contact_no'];
				$data['email'] = $myrow['email'];
				$data['pvgs'] = $myrow['pvgs'];
				$data['vpassword'] = $myrow['vpassword'];
				$data['iscurrent'] = $myrow['iscurrent'];
				$data['created_by'] = $myrow['created_by'];
				$data['vloginname'] = $myrow['vloginname'];				
			}
			return $data;
		
	}

	function users_id2code($usercode)
	{
		   require "conn.php";
			
		$sql="select a.vuser_id,(select org_srt from org where org_id=a.client_id)as org_sn,(select vendor_short_name from vendors where vendor_id=a.vendor_id)as ven_sn from login_master a where vuser_id='$usercode'	";
		  $rs = exec_qry($sql);
		   $result = $rs['result'];
		   $x = 0;
		   while ($myrow = mysqli_fetch_array($result)) 
		   {
				$vuser_id= $myrow['vuser_id'];
				$org_sn = $myrow['org_sn'];
				$ven_sn = $myrow['ven_sn'];
			}	
		if($org_sn == NULL)
		{
			$org_sn="000";
		}

		if($ven_sn == NULL)
		{
			$ven_sn="000";
		}
		$final_id =strtoupper($org_sn.$ven_sn.$vuser_id); 
		return $final_id;
	}

	function total_vendors($org_id,$vendor_id, $fdate, $tdate)
	{
	   require "conn.php";
	   $stmt1 = "";
	   $stmt2 = "";
	   $stmt3 = "";
		   if (($fdate != "") && ($tdate != "")) 
		   {
			  $stmt1 = " where day BETWEEN '$fdate-01' and '$tdate-01' ";
		   }

		   if ($org_id) 
		   {
			  $stmt2 = "  and org_id='$org_id' ";
		   }

		   if ($vendor_id) 
		   {
			  $stmt3 = "  and vendor_id='$vendor_id' ";
		   }

	   $sql = "select count(vendor_id)as vendors,month,year from vendor_data $stmt1 $stmt2 $stmt3 group by vendor_id";
	   //debug_txt($sql . ' tottalvendors');
	   $rs = exec_qry($sql);
	   $vendors = $rs['numrows'];
	   return $vendors;
	}

	function total_documents($org_id,$vendor_id, $fdate, $tdate)
	{
	   require "conn.php";
	   $stmt1 = "";
	   $stmt2 = "";
	   $stmt3 = "";
		   if (($fdate != "") && ($tdate != "")) 
		   {
			  $stmt1 = " and day BETWEEN '$fdate-01' and '$tdate-01' ";
		   }

		   if ($org_id) 
		   {
			  $stmt2 = "  and org_id='$org_id' ";
		   }

		   if ($vendor_id) 
		   {
			  $stmt3 = "  and vendor_id='$vendor_id' ";
		   }
	   
	   $sql = "select count(vendor_list_id)as totaldocuments,vendor_list_id,vendor_id,org_id from vendor_data where is_delete='N' $stmt1 $stmt2 $stmt3";
	   //debug_txt($sql . ' total_documentsXX');
	   $tot_docs = get_val($sql, 'totaldocuments');
	   $rs = exec_qry($sql);
	   return $tot_docs;
	}


	function total_verified($org_id,$vendor_id, $fdate, $tdate)
	{
	   require "conn.php";
	   $stmt1 = "";
	   $stmt2 = "";
	   if (($fdate != "") && ($tdate != "")) 
	   {
		  $stmt1 = " and day BETWEEN '$fdate-01' and '$tdate-01' ";
	   }

	   if ($org_id) 
	   {
		  $stmt2 = "  and org_id='$org_id' ";
	   }

	   if ($vendor_id) 
	   {
		  $stmt3 = "  and vendor_id='$vendor_id' ";
	   }
	   
	   $sql = "select count(vendor_list_id)as totaldocuments,vendor_list_id,vendor_id,org_id from vendor_data where is_delete='N' $stmt1 $stmt2 $stmt3 and status !=''";
	  //debug_txt($sql . ' total_verified');
	   $tot_docs = get_val($sql, 'totaldocuments');
	   $rs = exec_qry($sql);
	   return $tot_docs;
	}

	function pending_ckeck($org_id,$vendor_id, $fdate, $tdate)
	{
	   require "conn.php";
	   $stmt1 = "";
	   $stmt2 = "";
	   if (($fdate != "") && ($tdate != "")) 
	   {
		  $stmt1 = " and day BETWEEN '$fdate-01' and '$tdate-01' ";
	   }

	   if ($org_id) 
	   {
		  $stmt2 = "  and org_id='$org_id' ";
	   }

	   if ($vendor_id) 
	   {
		  $stmt3 = "  and vendor_id='$vendor_id' ";
	   }
	   
	   $sql = "select count(vendor_list_id)as totaldocuments,vendor_list_id,vendor_id,org_id from vendor_data where is_delete='N' $stmt1 $stmt2 $stmt3 and status =0";
	   //debug_txt($sql . ' pending_ckeck');
	   $tot_docs = get_val($sql, 'totaldocuments');
	   $rs = exec_qry($sql);
	   return $tot_docs;
	}


	function rejected_docs($org_id,$vendor_id, $fdate, $tdate)
	{
	   require "conn.php";
	   $stmt1 = "";
	   $stmt2 = "";
	   if (($fdate != "") && ($tdate != "")) 
	   {
		  $stmt1 = " where day BETWEEN '$fdate-01' and '$tdate-01' ";
	   }

	   if ($org_id) 
	   {
		  $stmt2 = "  and org_id='$org_id' ";
	   }

	   if ($vendor_id) 
	   {
		  $stmt3 = "  and vendor_id='$vendor_id' ";
	   }
	   
	   $sql = "select count(vendor_list_id)as totaldocuments,vendor_list_id,vendor_id,org_id from vendor_data  $stmt1 $stmt2 $stmt3 and status ='6' and is_delete='N'";
	  //debug_txt($sql . ' rejected_docs');
	   $tot_docs = get_val($sql, 'totaldocuments');
	   $rs = exec_qry($sql);
	   return $tot_docs;
	}




	function vendor_list($vendor_list_id, $df1, $df2, $df3)
	{

	   require "conn.php";

	   $sql = "SELECT field_type,vendor_field,field_name,display_srno,display FROM `vendor_data_list`  where vendor_list_id='$vendor_list_id' order by display_srno";

	   $rs = exec_qry($sql);
	   $result = $rs['result'];
	   $data = array();
	   $x = 0;
	   while ($myrow = mysqli_fetch_array($result)) 
	    {
		  $display[$x] = $myrow['display'];
		  $data[$x] = $myrow['vendor_field'];
		  $data1[$x] = $myrow['field_name'];
		  $data2[$x] = $myrow['field_type']; //varchar/INT
		  $FLD_NM  = 'a.' . $myrow['field_name'];

		  if ($data2[$x] == "Date") 
		  {
			 $FLD_NM = " DATE_FORMAT(a.$data1[$x], '%$df1-%$df2-%$df3') as $data1[$x] ";   //SA dtate Format
		  }

		  $field_name = $field_name . $cma . $FLD_NM;
		  $cma = ',';

		  $x++;
	    }

	   $DATA1['field_name'] = $field_name;
	   $DATA1['data'] = $data;
	   $DATA1['data1'] = $data1;
	   $DATA1['data2'] = $data2;
	   $DATA1['display'] = $display;

	   return  $DATA1;
	}


	function get_vendor_data($vendor_data_id, $vendor_list_id)
	{
	   require "conn.php";
	   $fields1 = vendor_list($vendor_list_id, 'Y', 'm', 'd');
	   $fields  = $fields1['field_name'];

	   $sql = "select $fields,a.month,a.year from vendor_data a where vendor_data_id='$vendor_data_id'";
	   $res = exec_qry($sql);
	   $result = $res['result'];
	   $fields_result = mysqli_fetch_fields($result);

	   foreach ($fields_result as $field) 
	    {
		  $sql = "select $fields,a.month,a.year from vendor_data a where vendor_data_id='$vendor_data_id'";
		  $res = exec_qry($sql);
		  $result = $res['result'];
		  while ($row = mysqli_fetch_array($result)) 
		  {
			 $data[$field->name] = $row[$field->name];
		  }
		  //debug_txt($sql);
	    }
	   return $data;
	}

	function pageination($limit, $limit_to, $numrows)
	{
		?><table>
		  <td align=right> <b>Pages : </b></td>
		  <td align=right>

			 [<span id="<?= $z; ?>-<?= $z + $limit_to; ?>" style="cursor:pointer" class="pg smaller" value="0,<?= $limit_to; ?>"> 1 </span>]
			 <?
			 for ($z = 0; $z < $numrows; $z++) 
			 {
				$z1++;
				if ($z1 == $limit) 
				{
					 $y++;
					 ?>[<span id="<?= $z + 1; ?>-<?= $z + 1 + $limit_to; ?>" style="cursor:pointer" class="pg smaller" value="<?= $z + 1; ?>,<?= $z + 1 + $limit_to; ?>"> <?= $y + 1; ?> </span>] <?
																																								  $z1 = 0;
																																							}
																																						 }
	
	?></td>
		  <td> &nbsp; </td>
	   </table><?
	}

	 function chk_menu_pvgs($menu_id, $flag)
	 {
		require "conn.php";
		$sql = "select menu_id from menu_master where menu_id='$menu_id' and pvgs like '%$flag%'";
		$rs = exec_qry($sql);
		$result = $rs['numrows'];
		return $result;
	 }

         function chk_site($org_id, $loc_id, $vendor_id, $site_id)
         {
            require "conn.php";
            $sql = "select * from org_vendors where org_id='$org_id' and loc_id='$loc_id' and vendor_id='$vendor_id' and site_id='$site_id'";
            $rs = exec_qry($sql);
            $result = $rs['numrows'];
            return $result;
         }



    function showstatus($m_selectname, $m_selectedval, $refresh)
    {
		require "conn.php";
		$sql = "select * from status order by status_nm";
		$res = mysqli_query($conn, $sql); ?>
		<font size=4 face=arial>
		<select name="<?= $m_selectname; ?>" id="<?= $m_selectname; ?>_id" class="form-select mb-3" style="width:250px;" <? if ($refresh == 1) { ?>onchange='form.submit()' <? } ?>>
	 <option value="">Select</option>
	 <?
		while ($rw = mysqli_fetch_array($res)) 
		{
		   $st_val = $rw['status_id'];
		   $st_nm = $rw['status_nm'];
		   $score = $rw['score'];
			?><option <? if ($st_val === $m_selectedval) { ?>selected<? } ?> value=<?= $st_val; ?>><?= $st_nm; ?></option><?
		} ?>
	</select>
	</font>
<? }

	 function debug_txt($txt)
	 {
		$myFile = "debug.txt";
		$ts1 = date('Y-m-d H:i:s');
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, $_SERVER["PHP_SELF"] . ' : Date : ' . $ts1 .  PHP_EOL);
		fwrite($fh, ' DATA : ' . $txt . PHP_EOL);
		fwrite($fh, '---------------------------------------------------------------------------------------------------------------------------------------------------' . PHP_EOL);
		fclose($fh);
	 }

function debug($sql_file){
include "conn.php";
$ts1=date('Y-m-d H:i:s');
$file_name=$_SERVER["PHP_SELF"];
$remote_ip=$_SERVER['REMOTE_ADDR'];
	$sql="insert into query_log values('','$file_name','$remote_ip','$sql_file','$ts1')";
	$result=mysqli_query($conn,$sql);
}



	 function exec_qry($m_sql)
	 {
		//echo $m_sql;
		if ($m_sql == "") 
		{
		} else 
		{
		   include "conn.php";
		   $data['result'] = mysqli_query($conn, $m_sql);
			debug($m_sql);
		   $data['last_id'] = mysqli_insert_id($conn);
		   $data['error'] = mysqli_errno($conn);
		   $data['numrows'] = mysqli_num_rows($data['result']);
		   $data['affected_rows'] = mysqli_affected_rows($conn);

		   return $data;
		}
	 }

    function showselectsize($selectname, $sql, $optval, $selval, $selectedval, $refresh, $size)
    { ?>

		<select id="<?= $selectname; ?>_id" name="<?= $selectname; ?>" class="form-select mb-3" aria-label=" Floating label select example" style="width:<?= $size ?>px;" <? if ($refresh == 1) { ?>onchange='form.submit()' <? } ?>>
		<option value="">Select</option>
		<? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval; ?>
				<option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
      <? 	} ?>
			</select>
<? 	}

     function showselectsize_nosel($selectname, $sql, $optval, $selval, $selectedval, $refresh, $size)
    { ?>

		<select id="<?= $selectname; ?>_id" name="<?= $selectname; ?>" class="form-select mb-3" aria-label=" Floating label select example" style="width:<?= $size ?>px;" <? if ($refresh == 1) { ?>onchange='form.submit()' <? } ?>>
		<? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval; 
			   ?><option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option><? 	
			} ?>
			</select>
<? 	}


     function showselectsize_options($sql, $optval, $selval, $selectedval)
    { ?>
   <? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval; 
			   ?><option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option><? 		
			}
    }


    function showselectsize_options_nosel($sql, $optval, $selval, $selectedval)
    { ?>
			<? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval; ?>
				<option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
   <? 		}
    }

    function showselectsize_opt($sql, $optval, $selval, $selectedval)
    { ?>
			<? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval;
			   ?><option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option><? 
			}
    }

    function showselectsize_dis($selectname, $sql, $optval, $selval, $selectedval, $refresh, $size, $disabled)
    { ?>
		<select id="<?= $selectname; ?>_id" name="<?= $selectname; ?>" class="form-select mb-3" aria-label=" Floating label select example" style="width:<?= $size ?>px;" <? if ($refresh == 1) { ?>onchange='form.submit()' <? }
                                                                                                                                                     if ($disabled == 1) 
		{
																																						?> disabled<?
                                                                                                                                                     } ?>>
																																			 
      <option value=''>Select</option>
      <? include "conn.php";
            $result1 = mysqli_query($conn, $sql);
            while ($orow1 = mysqli_fetch_object($result1)) 
			{
               $a1 = $orow1->$optval;
               $a2 = $orow1->$selval; ?>
				<option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
      <? 	} ?>
			</select>
<?  } 



	function show_month($width)
	{
	   require "conn.php";
	   $sql = "select * from month_master order by month";
	   $res = mysqli_query($conn, $sql);
	   if ($res) 
	    { ?>
		  <select class="form-select" id="floatingSelect" name="showmonth" aria-label="Floating label select example" style="width:<?= $width; ?>rem;">
			 <option selected>Select Month</option>
			 <?
			 while ($row = mysqli_fetch_array($res)) 
			 {
				$id = $row['month'];
				$month = $row['month_name'];
				?><option value="<?= $id; ?>"><?= $month; ?></option><?
			 }
			 ?></select>
	<?  }
	} 


	function show_year($width)
	{
	   require "conn.php";
	   $sql = "select * from year order by year_id";
	   $res = mysqli_query($conn, $sql);
	   if ($res) 
	    { ?>
		  <select class="form-select" id="floatingSelect" name="showmonth" aria-label="Floating label select example" style="width:<?= $width; ?>rem;">
			 <option selected>Select Year</option>
			 <?
			 while ($row = mysqli_fetch_array($res)) 
			 {
				$id = $row['year_id'];
				$year = $row['year_caption'];
				?><option value="<?= $id; ?>"><?= $year; ?></option><?
			 }
				?> </select><?
		}
	}

	function get_val($sql, $column_name)
	{
	   include "conn.php";
	   $m_name = '';
	   $resultstr = mysqli_query($conn, $sql);
	   if ($resultstr)
	   {
		  while ($myrow1 = mysqli_fetch_array($resultstr)) 
		  {
			 $m_name = $myrow1[$column_name];
		  }
	   }
	   return $m_name;
	}

	function chk_duplicate($sql)
	{
	   include "conn.php";
	   $result_test = mysqli_query($conn, $sql);
	   if (!mysqli_num_rows($result_test)) 
	   {
		  $flag = 0;  //no records present
	   } else 
	   {
		  $flag = 1;  //records present
	   }
	   return $flag;
	}



	function showOrg(...$par1)
	{
	   if ($_SESSION['PVGS'] != "A") return false;
	   global $sel_org, $mode;
	   if ($par1) 
	   {
		  $sql = "select org_id,org_name from org where org_id = $par1[0] order by org_name";
	   } else 
	   {
		  $sql = "select org_id,org_name from org order by org_name";
	   }

	   echo "<td><label for='inputEmail3' class='form-label'>Select Organization</label>";
	   if ($mode == 'edit') 
	   {
		  showselectsize("sel_org", $sql, 'org_id', 'org_name', $sel_org, '1', '300', '1');
	   } else 
	   {
		  showselectsize("sel_org", $sql, 'org_id', 'org_name', $sel_org, '1', '300');
	   }
	   echo "</td>";
	}



	function MasterDiv()
	{
	   global $sel_org, $sel_div, $mode;

	   $sql = "select distinct div_id, div_name from divisions_master ";
	   if ($_SESSION['ORG_ID'] != '' && $_SESSION['ORG_ID'] != 0) 
	   {
		  $org_id = $_SESSION['ORG_ID'];
		  $sql = "select distinct div_id, div_name from divisions_master where org_id = $org_id ";
	   }

	   if ($sel_org != '') $sql .= " where org_id = $sel_org";
	   $sql .= " order by div_name";
	   echo "<td><label for='inputEmail3' class='form-label'>Select Division</label>";
	   if ($mode == 'edit') 
	   {
		  showselectsize_dis("sel_div", $sql, "div_id", "div_name", $sel_div, '1', '250', '1');
	   } else 
	   {
		  showselectsize("sel_div", $sql, "div_id", "div_name", $sel_div, '1', '250');
	   }
	   echo "</td>";
	}



	function MasterLocation()
	{
	   global $sel_loc, $sel_div, $sel_org, $mode;

	   $sql = "select a.loc_id, b.state_name from location_master a, state_master b where a.state_id = b.state_id ";

	   if ($sel_org != '') $sql .= " and a.org_id = $sel_org";
	   if ($sel_div != '') $sql .= " and a.div_id = $sel_div";
	   $sql .= " order by b.state_name";

	   echo "<td><label for='inputEmail3' class='form-label'>Select Location</label>";
	   if ($mode == 'edit') 
	   {
		  showselectsize_dis("sel_loc", $sql, 'loc_id', 'state_name', $sel_loc, '1', '250', '1');
	   } else 
	   {
		  showselectsize("sel_loc", $sql, 'loc_id', 'state_name', $sel_loc, '1', '250');
	   }

	   echo "</td>";
	}


	function MasterVendor()
	{
	   // echo "comp -";
	   global $sel_loc, $sel_div, $sel_org, $sel_vendor, $og_id;
	   $flg = false;

	   $sql = "select distinct b.vendor_id, b.vendors_name from org_vendors a, vendors b where a.vendor_id = b.vendor_id ";
	   $og_id = $_SESSION['ORG_ID'];
	   if ($sel_org != '') 
	   {
		  $sql .= " and a.org_id = $sel_org";
		  $flg = true;
		  // $flag = "";
	   }
	   if ($og_id != '' && $og_id != 0) 
	   {

		  $sql .= " and a.org_id = $og_id";
		  $flg = true;
	   }
	   if ($sel_div != '') 
	   {
		  $sql .= " and a.div_id = $sel_div ";
		  $flg = true;
	   }
	   if ($sel_loc != '') 
	   {
		  $sql .= " and a.loc_id = $sel_loc ";
		  $flg = true;
	   }
	   if ($flg == true) 
	   {
		  $sql .= " order by b.vendors_name";
		  $flg = true;
	   }
	   if ($flg == false) 
	   {
		  $sql = "select vendor_id, vendors_name from vendors";
	   }
	   
	   echo "<td><label for='inputEmail3' class='form-label'>Select Vendor</label>";

	   showselectsize("sel_vendor", $sql, 'vendor_id', 'vendors_name', $sel_vendor, '1', '300');

	   echo "</td>";
	}

	function DocCombo()
	{
	   global $sel_vendor, $sel_doc;

	   $sql = "SELECT * FROM doc_type_master";

	   if ($sel_vendor != '') 
	   {
		  echo $sql = "SELECT a.doc_id, a.doc_name FROM doc_type_master a, vendor_doc b WHERE a.doc_id = b.doc_id AND b.vendor_id = $sel_vendor";
	   }

	   echo "<td><label for='inputEmail3' class='form-label'>Select Document</label>";

	   showselectsize("sel_doc", $sql, 'doc_id', 'doc_name', $sel_doc, '1', '250');

	   echo "</td>";
	}



	function call_msg($msg_name, $link)
	{ ?>
	   <script language="Javascript">
		  <? if ($msg_name != "")      //If message is not empty then
		  { ?>
			 alert("<?= $msg_name; ?>");
		  <? } ?>
		  <? if ($link != "")         //If link is not empty then
		  { ?>
			 window.location = "<?= $link; ?>";
		 <? } ?>
	   </script><? 
	}

	function alert($alert)
	{
	?>
	   <script>
		  alert('<?= $alert; ?>')
	   </script>
	<?
	}


	function docCap()
	{
	   $namespace = "namespace";
	   global $sel_cap;
	   $sql = "SELECT * FROM $namespace WHERE ns_code ='cap'";
	   showselectsize("sel_cap", $sql, 'id', 'ns_name', $sel_cap, '1', '250');
	}

	function getCount($table_name,$column_name,$condition)
	{
	   $sql = "SELECT COUNT($column_name) FROM $table_name";
	   if($condition !='')
	   {
		  $sql .=" WHERE $condition";
	   }
	   // exec_qry($sql);
	   $count = get_val($sql,"COUNT($column_name)");
	   return $count;
	}


	function state_Master()
   {
      $m_state_sql = "SELECT * FROM state_master";
      ?><td>
      <label for="inputEmail" class="form-label">Select State</label><?
      showselectsize("sel_state",$m_state_sql,"state_id","state_name",$sel_state,"1","250")
      ?></td><?      
   }


   function role_Master()
   {
	   global $sel_role;
      $m_role_sql = "SELECT * FROM pvgs";
      ?><td>
      <label for="inputEmail" class="form-label">Select Role</label><?
      showselectsize("sel_role",$m_role_sql,"flag","pvgs",$sel_role,"1","250")
      ?></td><?      
   }
?>