<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
$error="";
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$finaltotal=$userdur=$quesattend="";
$user_id=$_SESSION['id'];
if($_GET['test_id'])
{
    
}

$metatitle="";
$metadescription="";
$metarobots="index,follow";
$metacanonical="/";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/header.php"); ?>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-9">
            <div class="p-3">
                <h1 class="h4 font-weight-bold text-info rounded">Test Name</span></h1>
                <p class="text-secondary">Test Series</p>
            </div>
            <div class="row">  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-mortar-board border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $finaltotal;?>/100</p>
                    <p class="text-center"> 50% </p>
                    <p class="text-center text-secondary pb-3">Your score</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-clock-o border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $userdur;?></p>
                    <p class="text-center"> 50% </p>
                    <p class="text-center text-secondary pb-3">Time spend</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-child border p-3"></span></p>
                    <p class="text-center font-weight-bold">49.05/100</p>
                    <p class="text-center"> 50% </p>
                    <p class="text-center text-secondary pb-3">Your Rank</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-bar-chart border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $quesattend;?></p>
                    <p class="text-center"> 50% </p>
                    <p class="text-center text-secondary pb-3">Question attended</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3 pt-5"><br>
            <div class="card bg-light">
                <div class="card-body text-center">
                    <div><a href="" class="btn btn-outline-info">OVERALL ANALYSIS</button></a></div><br>
                    <div><a href="" class="btn btn-outline-info">OVERALL ANALYSIS</button></a></div><br>
                    <div><a href="" class="btn btn-outline-info">OVERALL ANALYSIS</button></a></div>
                </div>
            </div>  
        </div>
    </div>    
</div>    



<div id="pers_show_1"></div>
<div id="chart_div"></div>
      
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    google.charts.setOnLoadCallback(pers_show_1);
    function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['Mushrooms', 3],
        ['Onions', 1],
        ['Olives', 1],
        ['Zucchini', 1],
        ['Pepperoni', 2]
    ]);
    var options = {'title':'How Much Pizza I Ate Last Night',
                    'width':400,
                    'height':300};
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
    }

    function pers_show_1() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['mark', 40],
        ['total', 100]
    ]);
    var options = {
        pieSliceText: 'none',
        legend: { position: "none" },
        hAxis: { textPosition: 'none' },
    };
    var chart = new google.visualization.PieChart(document.getElementById('pers_show_1'));
    chart.draw(data, options);
    }
</script>
</body>   
</html> 
