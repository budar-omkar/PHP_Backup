<?


function chk_site($org_id,$loc_id,$vendor_id,$site_id){
   require "conn.php";
	$sql="select * from org_vendors where org_id='$org_id' and loc_id='$loc_id' and vendor_id='$vendor_id' and site_id='$site_id'";
	$rs=exec_qry($sql);
	$result = $rs['numrows'];
	return $result;
}



function showstatus($m_selectname, $m_selectedval, $refresh)
{
   require "conn.php";
   $sql = "select * from status order by status_nm";
   $res = mysqli_query($conn, $sql);
?>
   <select name="<?= $m_selectname; ?>" id="<?= $m_selectname; ?>_id" class="normal" <? if ($refresh == 1) { ?>onchange='form.submit()' <? } ?>>
      <option value="">Select</option>
      <?
      while ($rw = mysqli_fetch_array($res)) {
         $st_val = $rw['status_id'];
         $st_nm = $rw['status_nm'];
      ?>
         <option <? if ($st_val === $m_selectedval) { ?>selected<? } ?> value=<?= $st_val; ?>><?= $st_nm; ?></option>
      <? } ?>
   </select>
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


function exec_qry($m_sql)
{
   //echo $m_sql;
   if ($m_sql == "") {
   } else {
      include "conn.php";
      $data['result'] = mysqli_query($conn, $m_sql);
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
      while ($orow1 = mysqli_fetch_object($result1)) {
         $a1 = $orow1->$optval;
         $a2 = $orow1->$selval; ?>
         <option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
      <? } ?>
   </select>
<? } 

function showselectsize_options( $sql, $optval, $selval, $selectedval  )
{ ?>
      <option value="">Select</option>
      <? include "conn.php";
      $result1 = mysqli_query($conn, $sql);
      while ($orow1 = mysqli_fetch_object($result1)) {
         $a1 = $orow1->$optval;
         $a2 = $orow1->$selval; ?>
         <option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
      <? }
 } 

function showselectsize_dis($selectname, $sql, $optval, $selval, $selectedval, $refresh, $size,$disabled)
{ ?>

   <select id="<?= $selectname; ?>_id" name="<?= $selectname; ?>" class="form-select mb-3" aria-label=" Floating label select example" style="width:<?= $size ?>px;" <? if ($refresh == 1) { ?>onchange='form.submit()' <? } if ($disabled==1) {
    ?> disabled<?
   } ?>>
      <option value="">Select</option>
      <? include "conn.php";
      $result1 = mysqli_query($conn, $sql);
      while ($orow1 = mysqli_fetch_object($result1)) {
         $a1 = $orow1->$optval;
         $a2 = $orow1->$selval; ?>
         <option value="<?= $a1; ?>" <? if ($selectedval == $a1) { ?>selected <? } ?>><?= $a2; ?></option>
      <? } ?>
   </select>
<? } ?>


<?
function show_month($width)
{
   require "conn.php";
   $sql = "select * from month_master order by month";
   $res = mysqli_query($conn, $sql);
   if ($res) { ?>
      <select class="form-select" id="floatingSelect" name="showmonth" aria-label="Floating label select example" style="width:<?= $width; ?>rem;">
         <option selected>Select Month</option>
         <?
         while ($row = mysqli_fetch_array($res)) {
            $id = $row['month'];
            $month = $row['month_name'];
         ?>
            <option value="<?= $id; ?>"><?= $month; ?></option>
         <?
         }
         ?>

      </select>


<? }
} ?>
<?

function show_year($width)

{
   require "conn.php";
   $sql = "select * from year order by year_id";
   $res = mysqli_query($conn, $sql);
   if ($res) { ?>
      <select class="form-select" id="floatingSelect" name="showmonth" aria-label="Floating label select example" style="width:<?= $width; ?>rem;">
         <option selected>Select Year</option>
         <?
         while ($row = mysqli_fetch_array($res)) {
            $id = $row['year_id'];
            $year = $row['year_caption'];
         ?>
            <option value="<?= $id; ?>"><?= $year; ?></option>
         <?
         }
         ?>

      </select>


   <? }
   ?>

<?

}

function get_val($sql, $column_name)
{
   include "conn.php";
   $m_name = '';
   $resultstr = mysqli_query($conn, $sql);
   if ($resultstr) {
      while ($myrow1 = mysqli_fetch_array($resultstr)) {
         $m_name = $myrow1[$column_name];
      }
   }
   return $m_name;
}

// function selectOrg()
// {
   
// }

function showdivision($m_org){

   if ($m_org) {
    $sql="select * from divisions_master  where org_id='$m_org'";
    debug_txt($sql);
   }else{
   $sql="select * from divisions_master";
   }
   showselectsize("sel_div",$sql,"div_id","div_name",$sel_div,'','');
  
}

function showDiv(...$par)
{
    if($par) $sql="select div_id, div_name from divisions_master  where org_id='$par[0]'";

    else $sql="select div_id, div_name from divisions_master";

    echo "<td><label for='inputEmail3' class='form-label'>Select Organization</label>";
                            
    showselectsize("sel_div",$sql,"div_id","div_name",$sel_div,'1',''); 
    
    echo "</td>";
}

function showOrg(...$par){
    if($par)
    {
         $sqlquery1 = "select org_id, org_name from org where org_id =$par[0] order by org_name";
    }

    else {
        $sqlquery1 = "select org_id, org_name from org order by org_name";
    }

    echo "<td><label for='inputEmail3' class='form-label'>Select Organization</label>";
                            
    showselectsize("sel_org", $sqlquery1, 'org_id', 'org_name', $sel_org, '1', ''); 
    
    echo "</td>";
}

function showLoc(...$par){
   global $sel_loc;
   if($par) 
   {
       echo $sqlquery1 = "select * from location_master a, state_master b where a.state_id = b.state_id ";
   }
   else
   {
       $sqlquery1 = "select * from location_master a, state_master b where a.state_id = b.state_id and a.div_id = $par[0] ";
   }
   echo "<td><label for='inputEmail3' class='form-label'>Select Location</label>";
                           
   showselectsize("sel_loc", $sqlquery1, 'loc_id', 'state_name', $sel_loc, '1', ''); 
   
   echo "</td>";
}



function showVndr(...$par){
    global $sel_vendor;
    if($par) 
    {
     $sqlquery1 = "select org_id, org_name from location_master a, state_master b where org_id =$par[0] order by org_name";
    }
 
    else
    {
 
        $sqlquery1 = "select distinct * from org_vendors a, vendors b where a.vendor_id = b.
       vendor_id ";
    }
    echo "<td><label for='inputEmail3' class='form-label'>Select Location1</label>";
                            
    showselectsize("sel_vendor", $sqlquery1, 'vendor_id', 'vendor_name', $sel_vendor, '1', ''); 
    
    echo "</td>";
 }





// Master Functions

function MasterDiv()
{
   global $sel_org,$sel_div;
   
   $sql="select distinct div_id, div_name from divisions_master";
   
   if($sel_org != '') $sql .= "where org_id = $sel_org";

   echo "<td><label for='inputEmail3' class='form-label'>Select Organization</label>";
                            
   showselectsize("sel_div",$sql,"div_id","div_name",$sel_div,'1',''); 
   
   echo "</td>";

}

function MasterLocation()
{
   global $sel_loc,$sel_div,$sel_org;

   $sql = "select * from location_master a, state_master b where a.state_id = b.state_id";

   if($sel_org != '') $sql .=" and a.org_id = $sel_org";
   if($sel_div != '') $sql.=" and a.div_id = $sel_div";

   echo "<td><label for='inputEmail3' class='form-label'>Select Location</label>";
                           
   showselectsize("sel_loc", $sql, 'loc_id', 'state_name', $sel_loc, '1', ''); 
   
   echo "</td>";
}

function MasterVendor()
{
   global $sel_loc,$sel_div,$sel_org,$sel_vendor;

   $sql = "select distinct a.vendor_id, a.vendors_name from vendors a, vendor_data b where a.vendor_id = b.vendor_id";

   if($sel_org != '') $sql .= " and b.org_id = $sel_org";  
   if($sel_div != '') $sql .= " and b.div_id = $sel_div";  
   else $sql = "select vendor_id, vendors_name from vendors";
   echo $sql;
   echo "<td><label for='inputEmail3' class='form-label'>Select Vendor</label>";
                           
   showselectsize("sel_vendor", $sql, 'vendor_id', 'vendors_name', $sel_vendor, '1', '250'); 
   
   echo "</td>";
}

function DocCombo()
{
   global $sel_vendor,$sel_doc;
   
   $sql = "SELECT * FROM doc_type_master";

   if($sel_vendor != '')
   {
      $sql = "SELECT a.doc_id, a.doc_name FROM doc_type_master a, vendor_doc b WHERE a.doc_id = b.doc_id AND b.vendor_id = $sel_vendor";
   }
   echo "<td><label for='inputEmail3' class='form-label'>Select Vendor</label>";
                           
   showselectsize("sel_doc", $sql, 'doc_id', 'doc_name', $sel_doc, '1', '250'); 
   
   echo "</td>";
}


?>





<?

function MasterVendor2()
{
   // echo "comp -";
   global $sel_loc,$sel_div,$sel_org,$sel_vendor;
   $flg = false;
   $sql = "select distinct b.vendor_id, b.vendors_name from org_vendors a, vendors b where a.vendor_id = b.vendor_id ";
   echo 'og - '.$_SESSION['ORG_ID'];
   $og_id = $_SESSION['ORG_ID'];
   echo "org_id - $og_id";
   if($sel_org != '' )
   {
      $sql .= " and a.org_id = $sel_org";
      // $flag = "";
   }
      if($og_id != '' )
      {
         $sql .= " and a.org_id = $og_id";
      }
   
   if
   ($sel_div != '')
   {
       $sql .= " and a.div_id = $sel_div "; 
   }
   if($sel_loc != '')
   { $sql .= " and a.loc_id = $sel_loc "; 
   }
      //   $sql .= " ";
else
{
   $sql = "select vendor_id, vendors_name from vendors";
   $flg = true;
} 

if ( $flg != true )
$sql .=" order by b.vendors_name";
echo $sql;
   echo "<td><label for='inputEmail3' class='form-label'>Select Vendor</label>";
                           
   showselectsize("sel_vendor", $sql, 'vendor_id', 'vendors_name', $sel_vendor, '1', '250'); 
   
   echo "</td>";
}

function docCap($placeholder)
{
   
   $sql = "SELECT * FROM $namespace WHERE ns_code ='cap'";
   showselectsize("placeholder", $sql, 'id', 'ns_name', $placeholder, '1', '250'); 
}



   function getCount($table_name,$column_name,$condition)
   {
      $sql = "SELECT COUNT($column_name) FROM $table_name";
      if($condition !='')
      {
         $sql .=" WHERE $condition";
      }
      // exec_qry($sql);
      $count = get_val($sql,$column_name);
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
      $m_role_sql = "SELECT * FROM pvgs";
      ?><td>
      <label for="inputEmail" class="form-label">Select Role</label><?
      showselectsize("sel_role",$m_role_sql,"flag","pvgs",$sel_role,"1","250")
      ?></td><?      
   }
?>