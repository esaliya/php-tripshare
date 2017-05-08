<?php
session_start();
require('links.php');
require('db_access.php');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html;charset=iso-8859-1"/>
    <link rel="stylesheet" href="styles/style.css" type="text/css"/>
    <title>TripShare</title>
    <!--<script type="text/javascript" src="jquery-1.4.3.js"></script>
    <script type="text/javascript" src="search.js">  </script>-->

    <script type='text/javascript'>

    function formValidator(){
        // Make quick references to our fields
        var destination = document.getElementById('destination');
        var priceRange = document.getElementById('priceRange');
        var userName = document.getElementById('userName');
        var validation=true;
        // Check each input in the order that it appears in the form!

        if(notEmpty(destination))
        {
          if(isCorrectDestination(destination))
           {
               validation=true;

            }
          else
            {
                validation=false;
            }

        }

        if(notEmpty(userName))
        {
            if(isAlphabet(userName, "Please enter only letters for user name"))
            {
                validation=true;
            }
            else
            {
                validation=false;
            }
        }

       if(notEmpty(priceRange)){
            if(isNumeric(priceRange,"Please enter only numbers for cost"))
            {
                validation=true;
            }
            else
            {
                validation=false;
            }
        }

        if(validation)
        {
           document.searchform.action="search_helper.php";
            return true;
        }
        else
         {
             return false;
         }

    }

    function isCorrectDestination(destination){
         var   destination1=destination.value;
         var myDestinationList=destination1.split(";");
         var i=0;
        var destinationvalidtion;
         while(i<myDestinationList.length)
           {
              var currentDestination=myDestinationList[i];
              var eachDestination= currentDestination.split(",");
              if(eachDestination.length>3)
               {
                 alert("You can provide maximum only three parameters for destination");
                   destinationvalidtion=false;

               }
              else
              {
               if(eachDestination=="")
               {
                   alert("Invalid destination.");
                   destinationvalidtion=false;
               }
               else
               {

               var j=0;
              while(j<eachDestination.length)
               {
                   var currentDestinationPart=eachDestination[j];
                   if(isAlphabetString(currentDestinationPart,"Please enter only alphabets for destination"))
                   {
                      destinationvalidtion=true;

                   }
                   else
                   {
                       destinationvalidtion=false;
                   }
                   j++;

               }
               }
              }
           i++
           }
            if(!destinationvalidtion)
            {
                destination.focus();
            }
           return destinationvalidtion;
     }

function isAlphabetString(elem, helperMsg){
	var alphaExp = /^[a-zA-Z]+$/;
	if(elem.match(alphaExp)){
		return true;
	}else{
		alert(helperMsg);
		return false;
	}
}
    function notEmpty(elem){
        if(elem.value.length == 0){
           return false;
        }
        return true;
    }



    function isNumeric(elem, helperMsg){
	var numericExpression = /^[0-9]+$/;
        
	if(elem.value.match(numericExpression)){

		return true;
	}else{
		alert(helperMsg);
		elem.focus();
		return false;
	}
}

    function isAlphabet(elem, helperMsg){
        var alphaExp = /^[a-zA-Z]+$/;
        if(elem.value.match(alphaExp)){
            return true;
        }else{
            alert(helperMsg);
            //elem.focus();
            return false;
        }
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
        <?php echo genNavi("Search"); ?>
    </div>

<form  method=POST  name='searchform' onsubmit='return formValidator()'>
    <div class="left">
     <div class="left_box">
            <ul id='search'>
                <li><span><br><br><h3>Search By Destination</h3></span>
                    <table cellspacing=2>
                            <tr align="center" >
                                <td>
                     <p>Enter destination:
                         <label>
                             <input type='text' name='destination' id='destination'
                                    value="[city][,state/province][,country]"
                                     style="width:300px;color:gray;">
                         </label></p>
                        </td>
                        </tr>
                        </table>
                </li>
                <li><br><h3>Search By Attraction</h3><br>
                 <ul id='attraction'>
                    <li>
                    <table cellspacing=2 >
                            <tr align="center" >
                                <td>Select Attraction Type :</td>
                                <td><select name='attraction'>
                                        <option></option>
                                        <option>Museum</option>
                                        <option>State Park</option>
                                        <option>other</option>
                                 </select>

                        </td>
                        </tr>
                        </table>
                      </li>
                 </ul>
                </li>
                <li><span><br><br><h3>Search By Cost</h3></span>
                    <table cellspacing=2>
                            <tr align="center" >
                                <td>
                     <p>Cost less than: <input type='text' name='priceRange'  id='priceRange'></p>
                        </td>
                        </tr>
                        </table>
                </li>
                <li ><br><h3>Search By Rating</h3>
                <ul id="rating">
                    <li>
                    <table cellspacing=2 >
                            <tr align="center" >
                                <td>Select Rating :</td>
                                <td><select name="rating">
                                        <option></option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                    </select>

                        </td>
                        </tr>
                        </table>
                      </li>
                 </ul>
                </li>
                <li><br><h3>Search By User</h3>
                    <table cellspacing=2>
                            <tr align="center" >
                                <td>
                     <p>Enter User Name: <input type='text' name='userName' id='userName'  ></p>
                        </td>
                        </tr>
                        </table>
                </li>
            </ul>

        </div>
        <br><input type='submit' name='submit' value='Search' class='submit' />
    </div>
    <div class="right">

        <h3>Most Popular Trips:</h3>
        <?php echo(popularTrips()); ?>

        <h3>Recently Shared Trips:</h3>
        <?php echo(recentTrips()); ?>
    </div>
    <div class="footer">
        <p><a href="contact.php">Contact</a> <br/>
            &copy; Copyright 2010 TripShare</p>
    </div>
</div>


</form>

</body>
</html>