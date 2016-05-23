<?php

/* Part of SLOWWWShare V.1.0
 *
 * Copyright 2015 J. Venema S. jvs@slowwwshare.nl
 * Licensed under the GNU General Public License V.3 
 * http://www.gnu.org/licenses/
 *
 */
 
session_start();

ini_set("session.use_trans_id", "1");

//echo $_SESSION['auth'];

if ( $_SESSION['own'] != "yes" )
    {
        header ( "Location: owner.php" );
        exit(); 
    }



/***********************************************  LOGIN  PROCESSING****************************************************************/
//print_r( $_POST);

$uidEntered = FALSE;
$badFormatIncidents = 2;

if ( isset ( $_POST['iUser'] ) )

{
 //echo ("post iuser isset");
//CHECK UID


//echo $_SESSION['auth'];

	if ( empty ( $_POST['iUser'] ) )
    {
		echo ( " Empty string ");
    }
	else
		$uidEntered = TRUE;
      
     
		if ( !preg_match (   "/^[0-9A-Za-z,.@?-]+$/" , $_POST['iUser'] ) ) 
        {
           
		   echo ("<h1> Use only alpha numeric characters for the viewer name. One viewer name only, spaces are not allowed</h1>");
         }
		else
        {
           $badFormatIncidents -- ;
           $iUserId = strip_tags( $_POST['iUser'] );
           //echo $iUserId;
         }
  
  


//CHECK email

	if  ( !preg_match ( "/^[0-9A-Za-z,@.? -]+$/", $_POST['iMail'] ) ) 
    {
		echo ("<h2>Use only allowed characters for the email, including @ </h2>");
        //echo   $_POST['iMail'];
    }
	else
    {
        $badFormatIncidents --;
        $iMail = strip_tags( $_POST['iMail'] );
        
       //echo $iMail;
    }
	
 
 
 
 
 
 
		  
}



/******************  CONSTANTS ****************  GLOBALS ********* INITIALIZATIONS **********************************/



include ( 'INCLUDE/initDB.php');

	
include ( 'INCLUDE/selectUrl.php');
$urlArray = selectUrl ();
//print_r( $urlArray);
$url = $urlArray[0];



/***************************  HTML START ******************************************************************/


$docstring = "!doctype html";

Echo 
("

<$docstring>



<head>
    <meta charset='utf-8'/>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'/>
    <link rel='stylesheet' media='all' href='STYLE/css/bootstrap.css'/>
	<link rel='stylesheet' media='all' href='STYLE/css/bwsOverride.css'/>
	
    <title> Add a viewer </title>
</head>


<body>


   

<div class='navbar navbar-default navbar-fixed-top'>

		<div class='container'>


			<button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
            </button>
          
          
   	       	<a class='navbar-brand ' href='www.slowwwshare.nl'><span class='Sofia'> SLOWw<small>w</small>Share </a></span>


         	<div class='navbar-collapse collapse'>
            	<ul class='nav navbar-nav'>

              		<li ><a href='albums.php'>Pages </a></li>
			  		<li class='active'><a href='viewers.php'>Viewers</a></li>
              		
              	
			  		<li><a href='parkingLot.php'>Parkinglot</a></li>
					<li><a href='allLogins.php'> Login history </a></li>
            	</ul>
       		</div> 
     	</div>    
	</div>

	
 
 
 <div class='container'>

	<div class='jumbotron '>

	<header class= 'text-center'>

				
		<h1 > <span class='text-orange'> Add a viewer </span> </h1>
				

	</header>
</div>

</div>
<hr>	

");



/****************************************************    MAIN     **********************************************************/

//echo ("In main nu");

if ( ( $uidEntered ) and ( $badFormatIncidents < 1)  )

{
    
	$today=date_create();
	$plusDay = date_add ($today, date_interval_create_from_date_string('1 day'));
	$expire = date_format ( $plusDay, 'ymdHms');
	//echo 'today==='; echo $expire; print_r($today);
	
	$tempww = createUniquePW();
	
	/*
	
      */ 
	
	
	$invite = 'open';
    $result = insertDB($iUserId, $iMail, $invite,$tempww, $expire );
  
	
  	
	if  ( $result == 1 ) 

	{
		
		$viewer=$iUserId; 
		
		echo("
		
				<h2>New viewer has been added<h2>
				
				<div class='row text-center'>

						<a class='btn btn-lg text-orange btn-shadow margin-15' href='viewers.php'> Back to viewer overview </a>
						
						<a class='btn btn-lg text-l-yellow btn-shadow margin-15' href='sendInvite.php?gViewer=$viewer'> Send email invitation </a> 
				</div>
				
				
				
			");
		
		
		
		exit();
   }
   
   elseif ( $result ==1062 )  //DUPLICATE KEY
   {
	echo ("

			<br><h2>This viewer already exists, please use another name</h2>
			
			<div class='row text-center'>

				<a class='btn btn-lg text-orange btn-shadow margin-15' href='addViewer.php'> Try again </a>
			</div>
			
			
			
			
			");
	
	exit();
   }
   
   
   
   else
   {
		echo ("
		
				</br><h2>Oooops we have a problem with the viewer , please try again </h2>
				</br>$result
				
				<div class='row text-center'>

					<a class='btn btn-lg text-l-blue btn-shadow margin-15' href='addViewer.php'> Try again </a>
				</div>
				
				
				");
		exit();
   }
   
}

  



     
/***************************  START FORM   ***********************************************************/
     
echo ("
	 
	 
<div class='container'>


	<div class ='row'>

		<form  action='addViewer.php'  method='post' id= 'viewerform'   >



		<div class='col-lg-8 col-lg-offset-2'>
    
  
               
                <p><span class='sm-heading text-orange '> Name of the new viewer </span>
                 <input  type ='text' class='form-control'   name='iUser' id='iUser'  maxlength ='49'     pattern='[0-9A-Za-z,@.? -]+' required='true' autofocus > </p>

				<h2></h2>
           
					
               <p>    <span class='sm-heading text-orange '> Existing valid email address for this viewer </span>  
				<input  type ='email' class='form-control'   name='iMail' id='iMail'  maxlength ='49'     pattern='[0-9A-Za-z,@.? -]+' required='true' > </p>
		
				
						
			<input type='submit' value='Submit' class='btn btn-lg pull-right btn-shadow' >  
            	
				
		</div>                
	</div>

	
	<div id='messagediv'>
	
	</div>

<hr>
<div class='row text-center'>

  <a class='btn btn-lg btn-shadow margin-15 text-l-blue' href='viewers.php'> Back </a>

</div>
  
<hr>


");
  


  
  
/*************** FUNCTION ***************** CREATE UNIQUE password  ***************** RETURNS password ****************/


function createUniquePW()

{
 
    $alphabetLC = "qwertyuiopasdfghjklzxcvbnm" ;
	$alphabetUC = "QWERTYUIOPASDFGHJKLZXCVBNM" ;
    $randomNumber = rand ( 0, 99 );
    $rn1 = rand (1, 26);
    $rn2 = rand (1, 26);
	$rn3 = rand (1, 26);
    $rn4 = rand (1, 26);
    $randomLetter1 =  $alphabetLC[$rn1];
    $randomLetter2 = $alphabetLC[$rn2];
	$randomLetter3 =  $alphabetUC[$rn3];
    $randomLetter4 = $alphabetUC[$rn4];
	
    $wachtw = $randomNumber.$randomLetter1.$randomLetter2.$randomLetter3.$randomLetter4 ;

	//echo " created uniqueWW=".$wachtw;

return $wachtw;

}


/******************   FUNCTION  ************************** INSERT INTO   DB  ******************************************/

function insertDB($iUserId, $iMail, $invite, $tempww, $expire)
{
    
    //echo ("In insert nu");
    
    $cxn = initDB();

    if ( ! $stmt = mysqli_prepare($cxn, 'INSERT INTO viewer (ziener, post, invite,tempww, expire) VALUES (?, ?, ?, ?, ?)') )
    {
         $result = mysqli_error ( $cxn );
        //echo ('<p> mysqli  prepare statement error insert </p> <p>$message </p>');
     }

    else
    {
          mysqli_stmt_bind_param($stmt, 'sssss', $iUserId, $iMail, $invite, $tempww, $expire);
        
            				

		   if ( !mysqli_stmt_execute($stmt) )
            {
                   $result = mysqli_errno($cxn);
				   $message = mysqli_error ( $cxn );
                    //echo ('<p> Probleem  tijdens execute insert </p> '.$message);
					
            }
            else
            {
                    $result = mysqli_stmt_affected_rows($stmt);
                    //echo ('Row inserted '); echo $result;
            }
       
        
        mysqli_stmt_close($stmt); 
    }
 
    return $result;
}

include ('INCLUDE/script.html');

?>



</div>

</div>

</body>

</html>