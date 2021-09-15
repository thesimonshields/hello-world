<?php
$id=0;
$url = $_REQUEST["url"] ;
$doit= $_REQUEST["doit"];
$sms = $_REQUEST["sms"];
$street = $_REQUEST["street"];
$number = $_REQUEST["number"];
$racks = $_REQUEST["racks"];
$rack = $_REQUEST["rack"];
$nlots = $_REQUEST["nlots"];
$slot = $_REQUEST["slot"];

$me = $_SERVER['PHP_SELF'] ;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
$filename = "racks.json";
$string = file_get_contents($filename);
$json = json_decode($string, true);
$smsoptions = "";
if ($sms=="") $sms=$json["sms"][0];
foreach ($json["sms"] as $option)
{
	if ("$sms"=="$option")
	{
		$smsoptions .= "<option value='$option' selected>$option</option>\n";
	} else 
	{
		$smsoptions .= "<option value='$option'>$option</option>\n";
	}
}
print <<<EOL
<form name=mailsortform method=post action="$me" >
<table align=center>
<tr><th colspan=2 align=center><h1><span class="a">Mail Sort</span></h1>
<tr><td align=right><span class="a">SMS</span><td><select name=sms OnChange="form.doit.value='sms';form.submit();">
$smsoptions
</select>
<input name=doit type=hidden value="">
EOL;
$streetoptions="";

if ("$sms" != "") {
	$streetoptions="";
	if ($street=="") $street = $json["$sms"]["streets"][0];
	foreach ($json["$sms"]["streets"] as $option)
	{
		if ("$street"=="$option")
		{
			$streetoptions .= "<option value='$option' selected>$option</option>\n";
		} else 
		{
			$streetoptions .= "<option value='$option'>$option</option>\n";
		}
	}
	$color=0;
	if ("$street" != "" && "$sms" != "")
	{
		$nlots = $json["$sms"]["$street"]["nlots"]["$number"] ;
		$slot = "";
		$rack = "";
		if ($nlots) 
		{
			$rack = $nlots[0];
			$slot = $nlots[1];
		}
		$rgba = $json["$sms"]["$street"]["rgba"];
		$color = sprintf("#%02x%02x%02x", $rgba[0],$rgba[1],$rgba[2]);
		$racks = "";
		foreach ($json["$sms"]["$street"]["racks"] as $s) {
			$racks .= "$s,";
		}
		$racks = substr($racks, 0, strlen($racks)-1);
	}
	print <<<EOT
<style>
span.a {
  display: inline; /* the default for span */
  width: 100px;
  height: 100px;
  padding: 5px;
  border: 1px solid blue;  
  background-color: $color; 
}
.button {
  background-color: $color; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}
</style>
<tr><td align=right><span class="a">Street</span><td><select name=street OnChange="form.doit.value='street';form.number.value='';form.submit();">
$streetoptions
</select>
<tr><td align=right><span class="a">Racks</span><td><input name=racks type=text value="$racks" disabled>
<tr><td align=right><span class="a">Number</span><td><input name=number type=text size=2 OnChange='form.number.value=form.number.value.toUpperCase();' value="$number">
<tr><td align=right><span class="a">Rack/Slot</span><td><input name=rack type=text size=2 value="$rack" disabled>/<input name=slot type=text size=2 value="$slot" disabled>
<tr><td colspan=2 align=center><button class=button name=find size=10 OnClick="form.doit.value='find';form.submit();"><span class="a">Find</span></button></span>
EOT;
}

?>
</table>
</form>
</body>
</html>
