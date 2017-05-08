<?php
/**
 * Created by PhpStorm.
 * User: goyals
 * Date: Nov 28, 2010
 * Time: 5:03:46 PM
 * To change this template use File | Settings | File Templates.
 */

session_start();
require('links.php');
require('db_access.php');
dbConnect();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
    <link rel="stylesheet" href="styles/style.css" type="text/css"/>
    <title>TripShare</title>
    <script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
    
    <script type="text/javascript">
        
  function validateAndSubmit0 (){
            var error = false;
            var element = document.getElementById('fnamebox');
            if (element.value == "") {
                makeError('fnamebox', "First Name cannot be empty!");
                error = true;
            }
            element = document.getElementById('lnamebox');
      
             if (element.value == "") {
                makeError('lnamebox', "Last Name cannot be empty!");
                error = true;
            }

            element = document.getElementById('emailbox');

             if (element.value == "") {
                makeError('emailbox', "Email cannot be empty!");
                error = true;
            }else if(echeck(element.value)==false){
                makeError('emailbox', "Email Format is not correct!");
                error = true;
             }

             element = document.getElementById('subjectbox');

             if (element.value == "") {
                makeError('subjectbox', "Subject cannot be empty!");
                error = true;
            }
        
            if (!error) {
                  document.form0.action="contact_helper.php";
                  document.form0.submit();
            }
        }

   function makeNormal(id) {
         var element = document.getElementById(id);
            if (element.style.color == "red") {
                element.value='';
                element.style.color="#454545";
                element.style.font="Tahoma,sans-serif";
            }
        }

   function cancelTrip(formId) {
            if (confirm("Are you sure you want to discard current trip information?")) {
                var form = document.getElementById(formId);
                form.action= "contact_helper.php?cancel=true";
                form.submit();
            }
        }
  
    function makeError(id, msg) {
            var element = document.getElementById(id);
            element.style.color = "red";
            element.style.fontSize="inherit";
            element.style.padding="3px 0";
            element.value = msg;
        }

function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true
	}
    
</script>

</head>
<body>
<div class="content">
    <div class="header">
        <div class="top_info_right">
        <?php echo genTopLinks(); ?>
            <br/>
        </div>
        <div class="logo">
            <h1><a href="index.php" title="TrpShare Home"><span class="dark">Trip</span>Share</a></h1>
        </div>
    </div>
    <div class="bar" id="navi">
        <!--Navigation Bar-->
    <?php echo genNavi("Contact"); ?>
    </div>
    <div class="left">
      <div class="left_box">
          <p>If you have any questions, comments, or suggestions regarding this website,
        feel free to contact the administrators by filling out the form below.<br/><br/></p>
      <form id="formId0" name="form0" action="" method="post">
        <table>
          <tr>
            <td>First Name: </td>
            <td>
              <input id="fnamebox" type="text" name="fname" size="30" maxlength="15"  onclick="makeNormal('fnamebox');"/>
            </td>
          </tr>
          <tr>
            <td>Last Name: </td>
            <td>
              <input id="lnamebox" type="text" name="lname" size="30" maxlength="15" onclick="makeNormal('lnamebox');"/>
            </td>
          </tr>
          <tr>
            <td>Email:</td>
            <td>
              <input id="emailbox" type="text" name="email" size="30" maxlength="60" onclick="makeNormal('emailbox');"/>
            </td>
          </tr>
          <tr>
            <td>Subject: </td>
            <td>
              <input id="subjectbox" type="text" name="subject" size="50" maxlength="100" onclick="makeNormal('subjectbox');"/>
            </td>
          </tr>
          <tr>
            <td>Question:</td>
            <td>
              <textarea  name="question" cols="40" rows="15"></textarea>
            </td>
          </tr>
        <tr>
      <td colspan="2" align="right">
      <input name="submit" class="submit" type="submit" value="Submit" onclick="validateAndSubmit0();return false;"/>
       <input class="submit" type="reset" value="Clear" onclick="cancelTrip('formId0');"/></td></tr>
        </table>
      </form>
    </div>
    </div>
     <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
 </div>
</body>
</html>
 
