<!DOCTYPE html>
<html>
<head>
<script>

function save()
{
val = document.myform.text_area.value;
mydoc = document.open();
mydoc.write(val);
mydoc.execCommand("saveAs",true,"text.txt"); //you can change the .txt to your extention
}
</script>

<script>
function cancel(){
 langdingPage();
}
</script>

<center>
<h1><p style = "color: blue;"><u>Promote-a-Cause</u></p></h1>

</head>
<body>
<form name="myform">
<table>
    <tr><td><b>Cause: </b><input type="text" name="name"/></td></tr>
    <tr><td colspan="2"><b>Description: </b></td></tr>
    <tr><td colspan="5"><textarea name="text_area" id="text_area_id" rows="14" cols="40"></textarea></textarea></td></tr>
</table>
<input type="button" onClick="save();" value="Save">
<button onClick="window.location.href='landingView();" />Cancel</button>

</form>
</body>
</html>
