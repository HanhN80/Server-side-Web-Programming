<?php
mysql_connect("localhost:8080", "root", "");
mysql_select_db("commentbox");
$name=$$_POST('name');
$comment=$_POST('comment');
$submit=$_POSt('submit');
$dbLink = mysql_connect("localhost:8080", "root", "");
	mysql1_query("SET character_set_client=utf8", $dbLink);
	mysql1_query("SET character_set_connection=utf8", $dbLink);
if(submit){
if ($name&&$comment){
$insert=mysql_query("INSERT INTO comment (name,comment) VALUES ('$name), '$connect') ")
}
else
{
echo "please fill out the fields";
}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Comment box</title>
</head>
<body>
<center>
<form action="commentindexsample.php" method="POST">
<table>
	<tr><td>Name: <br><input type="text" name="name"/></td></tr>
	<tr><td colspan="2">Comment: </td></tr>
	<tr><td colspan="5"><textarea name="comment" rows="10" clos="50"></textarea></td></tr>
	<tr><td colspan="2"><input type="submit" name="submit" value="comment"></td></tr>
</table>
</form>

<?php
$dbLink = mysql_connect("localhost:8080", "root", "");
	mysql_query("SET character_set_results=utf8", $dbLink);
	mb_language('uni');
	mb_interal_encording('UTF-8');

$getquery=mysql_query("SELECT * FROM commenttable ORDER BY id DESC");
while($row=mysql_fetch_assoc($getquery)){
$id=$row['id'];
$name=$row['name'];
$comment=$row['comment'];
echo $name . '<br/>' . '<br/>' . $comment . '<br/>' . '<br/>' . '<hr size="1"/>';

}


?>
</body>
</html>
</head>

