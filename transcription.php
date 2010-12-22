<?php
require_once("connect.php");
$text=$_REQUEST['TranscriptionText'];
$recordingURL=$_REQUEST['RecordingUrl'];
$phoneNumber=$_REQUEST['From'];
$callSid=$_REQUEST['CallSid'];
$query="insert into messages(phoneNumber, callSid, messageFile, messageText, timestamp)
values ('$phoneNumber', '$callSid', '$recordingURL', '$text', now())";
mysql_query($query) or die(mysql_error());
echo $query;
?>