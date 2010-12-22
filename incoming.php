<?php
require_once("connect.php");
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n
<Response>";

if(isset($_REQUEST['Digits']))  //the user made a choice
  {
    $digits=$_REQUEST['Digits'];
    if($digits=='1'){
      echo "<Say>Record a note. Hang up when you're done</Say>";
      echo "<Record transcribe='true' transcribeCallback='transcription.php'/>";
    }
    else if($digits=='2')
      {
	$phoneNumber=$_REQUEST['From'];
	$query="select *,
date_format(timestamp, '%W the %D') as 'formattedDate'
 from messages where phoneNumber='$phoneNumber' ORDER BY timestamp DESC";

	$result=mysql_query($query);
	$query = $query ."\n $phoneNumber";
	$rows=mysql_num_rows($result);
	//	echo $query;
	//	echo $rows;
	for($i=0;$i<$rows; $i++)
	  {
	    $row=mysql_fetch_array($result);
	    $date=$row['formattedDate'];
	    $url=$row['messageFile'];
	    $printedRow=print_r($row, true);
	    echo "<Say>$date</Say>";
	    echo "<Play>$url</Play>";
	    $query=$query . "\n here's a line:  $date $url";
	    $query=$query . "\n $printedRow";
	  }
	echo "<Say>That's all your notes</Say>
	<Redirect>".$_SERVER['PHP_SELF']."?repeat=1</Redirect>";
      }
    else{
      echo "<Gather action='verify.php?' numDigits='4'>";
      echo "<Say>Enter your verification code</Say>";
      
    }
  }


else
  {
    echo "<Gather action='".$_SERVER['PHP_SELF']."' numDigits='1'>";
    if(!isset($_REQUEST['repeat'])) //this is the first time we've run the script
      {
      echo "<Say>Welcome to Whisper</Say>";
	$phoneNumber=$_REQUEST['From'];
	$query="select * from messages where phoneNumber='$phoneNumber'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0)
	  {
	    playIntro();
	  }

      }
    echo "<Say>To leave a note, press 1.  To hear your notes, press 2</Say>
          </Gather>";
  }

echo "</Response> ";

function playIntro()
{
  echo "<Say>Whisper is a fast, simple scratch pad for voice notes.  You can record notes for yourself, or listen to notes you've saved for yourself.  
Your notes are tied to your phone number, so only you can access them.</Say>";
}

?>