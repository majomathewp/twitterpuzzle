<!DOCTYPE html>
<?php
//error_reporting(0);
//set_time_limit(60);
//Tweet Count Starts here

$handl=$_GET["handle"];
$opts = array(
  'http'=>array(
		'method'=>"GET",
		'header'=>
			"Accept-language: en\r\n".
			"Content-Type: application/json\r\n"

      )
);

$context = stream_context_create($opts);
$rtcount=0;
$tjson = file_get_contents("http://api.twitter.com/1/statuses/user_timeline/".$handl.".json?count=20", false, $context);
$str = json_decode($tjson, true); 
$tcount = count($str);
$jesus = array();
$tr=0;
for($k=0;$k<$tcount;$k++)
{
	if(!$str[$k]['in_reply_to_status_id_str']&&$rtcount<10)
	{
		$rtcount++;		
				

		$tjson1 = file_get_contents("http://api.twitter.com/1/statuses/".$str[$k]['id_str']."/retweeted_by.json", false, $context);
		$str1 = json_decode($tjson1, true);
		$tcount1 = count($str1);
		for($p=0;$p<$tcount1;$p++)
		{
		

			$jesus[$tr]['count'] = $str1[$p]['followers_count'];		
			$jesus[$tr]['name'] = $str1[$p]['screen_name'];	
			$jesus[$tr]['profile_image'] = stripcslashes($str1[$p]['profile_image_url_https']);
			$jesus[$tr]['profile_image'] = str_replace("_normal.jpeg", ".jpeg", $jesus[$tr]['profile_image']);
			$tr++;
		
		
		}

		
	}
}

?>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <title>Twitter Illuminati</title>
  <link rel="stylesheet" href="../css/style.css?version=1" type="text/css" media="screen" charset="utf-8">
  <meta name="viewport" content="width=device-width,user-scalable=no,maximum-scale=1">

  <script type="text/javascript" src="../js/menu.js?version=1" charset="utf-8"></script>
<style>
nav > a {
background: url(<?php echo "'https://api.twitter.com/1/users/profile_image/".$handl.".json?size=original'"?>);
-moz-background-size:200px 200px; /* Old Firefox */
background-size: 300px;
background-position:center;
background-repeat:no-repeat;
}
</style>

</head>
<body>
  

  
  <div id="app">
    <h1>Im <span class="punch"><?php echo $str[0]['user']['name'] ?>.</span> Click Me ! </h1>
	<br/>
    <nav id="full">
      <a href="#"></a>
      <ul>

      <?php

	$j=1;
	
	array_multisort($jesus,SORT_DESC);

	for($k=0;$k<$tr&&$j<$tr;$k++)
	{
			if($jesus[$k]['name']==$jesus[$j]['name'])
			{
				$jesus[$k]['count'] = $jesus[$k]['count'] + $jesus[$j]['count'];
				$jesus[$j]['count']=0;
				$jesus[$j]['name']=NULL;
			}
			$j++;
					
	}

	array_multisort($jesus,SORT_DESC);



	for($k=0;$k<12;$k++)
	{
		if($jesus[$k]['count']>0)
		{

			echo "<li><a href='#'><img src = '".$jesus[$k]['profile_image']."' title='".$jesus[$k]['count']."' width='100' height='100' /></a></li> ";
			
		

		}
	}



?>
      </ul>
    </nav>
    <script type="text/javascript" charset="utf-8">
      var full = new Menu(document.querySelector('#full'), {
        degrees: 360,
        offset: 180,
        radius: 180
      });
    </script>
   
  </div>
 

  <script type="text/javascript" charset="utf-8">
    var m = new Menu(document.querySelector('#arc'), { radius: 130 });
    var app = document.querySelector('#app');
    if(app.ontouchmove !== undefined){
      app.addEventListener('touchmove', function(){
        m.close();
      });
    } else {
      app.addEventListener('scroll', function(){
        m.close();
      })
    }
  </script>
</body>
