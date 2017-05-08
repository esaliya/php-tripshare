<?php
/**
 * Created by PhpStorm.
 * User: saliya
 */

if (isset($_POST['jsonText'])) {
    $jsonText = $_POST['jsonText'];
    echo($jsonText);

    $circle = json_decode($jsonText);
    echo(count($circle->{'names'}));
    echo($circle->{'radius'});
    echo($circle->{'names'}[1]);

    foreach($circle->{'names'} as $name){
        echo('<br>');
        echo($name);
    }

}

$cars = array();
$cars["brand1"] = "Toyota";
$cars["brand2"] = "Nissan";
$cars["brand3"] = "Lexus";
$cars["types"] = array("suv", "sedan");

$carsString = json_encode($cars);


?>

<script type="text/javascript">
    function area() {
        return this.radius * this.radius * Math.PI;
    }

    function Circle(r) {
        // properties
        this.radius = r;
        this.names = new Array();

        // methods
        this.area = area;

    }

    var c = new Circle(2);
    c.names.push('john');
    c.names.push('march');
    c.names.push('tuck');
    //    alert(c.area());

    //    alert(jsontext);


    function postToSelf() {
        c.names.push(document.getElementById('nameBx').value);
        document.getElementById('jsonText').value = JSON.stringify(c);
        document.submitFrm.action = "jsontest.php";
        document.submitFrm.submit();


    }

    var cars = JSON.parse('<?php echo($carsString); ?>');
    alert(cars.brand1);
    var types = cars.types;
    for (var i = 0; i < types.length; i++) {
        alert(types[i]);
    }
    
</script>

<html>
<body>
<p>Good! it works.</p>

<div id="nameDiv">
    <label>
        <input id="nameBx" type="text"/>
    </label>
</div>

<form onsubmit="postToSelf();" action="" name="submitFrm" method="post">
    <label>
        <input id="submit" name="submit" type="submit" value="Submit"/>
    </label>
    <label>
        <input type="hidden" name="jsonText" id="jsonText" />
    </label>
</form>


</body>
</html>
