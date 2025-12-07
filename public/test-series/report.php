<?php
session_start();
define('myprivateaccess', TRUE);
$error=$finaltotal=$my_time=$atnd_ques=$toppers=$my_rank="";
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$user_id=$_COOKIE['id'];
if($_GET['test_id'])
{
    $test_id=$_GET['test_id'];
    $testsql="SELECT * FROM `test` WHERE `id`='$test_id'";
    $testres=$conn->query($testsql);
    while($testrow = $testres->fetch_assoc())
    {
        $test_series=$testrow['test_series'];
        $testname=$testrow['name'];
        $sub_name=$testrow['subname'];
        $total_ques=$testrow['count'];
        $marks=$testrow['marks'];
        $actual_time=$testrow['duration']*60;
        $startdate=$testrow['startdate'];
        $enddate=$testrow['enddate'];
    }
    $repsql="SELECT * FROM `report` WHERE `user_id`='$user_id' AND `test_id`='$test_id'";
    $repres=$conn->query($repsql);
    while($reprow = $repres->fetch_assoc())
    {
        $c_ansd=$reprow['c_ansd'];
        $n_ansd=$reprow['n_ansd'];
        $m_ansd=$reprow['m_ansd'];
        $c_notans=$reprow['c_notans'];
        $n_notans=$reprow['n_notans'];
        $m_notans=$reprow['m_notans'];
        $c_corrt=$reprow['c_corrt'];
        $n_corrt=$reprow['n_corrt'];
        $m_corrt=$reprow['m_corrt'];
        $c_wrng=$reprow['c_wrng'];
        $n_wrng=$reprow['n_wrng'];
        $m_wrng=$reprow['m_wrng'];
        $c_mark=$reprow['c_mark'];
        $n_mark=$reprow['n_mark'];
        $m_mark=$reprow['m_mark'];
        $c_negmk=$reprow['c_negmk'];
        $n_negmk=$reprow['n_negmk'];
        $m_negmk=$reprow['m_negmk'];
        $c_total=$reprow['c_total'];
        $n_total=$reprow['n_total'];
        $m_total=$reprow['m_total'];
        $my_score=$reprow['finaltotal'];
        $perc_mark=($my_score/$marks)*100;
        $my_time=$reprow['userdur'];
        $perc_time=($my_time/$actual_time)*100;
        $atnd_ques=$c_ansd+$n_ansd+$m_ansd;
        $perc_ques=($atnd_ques/$total_ques)*100;
        $crt_ans=$c_corrt+$n_corrt+$m_corrt;
        $accuracy=($crt_ans/$atnd_ques)*100;
    }
    $t_count=$t_tot_mark=$t_tot_time=0;
    $ranksql="SELECT * FROM `report` WHERE `test_id`='$test_id' ORDER BY `finaltotal` DESC, `userdur` ASC";
    $rankres=$conn->query($ranksql);
    $rank=0; $t_rank=1;$t_mark="";
    $t_avg_mark=$t_avg_dur=$t_avg_atnd=$t_avg_accu=0;
    while($rankrow = $rankres->fetch_assoc())
    {
        $mine_in="";
        if($t_mark  !=$rankrow['finaltotal'])
        {
            $rank++;
        }
        if($user_id==$rankrow['user_id'])
        {
            $my_rank = $rank;
            $mine_in = "text-success font-weight-bold";
        }
        if($rank<=10)
        {
            $name="Topper";
            $topid=$rankrow['user_id'];
            $top="SELECT `name` FROM `users` WHERE `id`='$topid'";
            $topres=$conn->query($top);
            if($topres->num_rows>0)
            {
                $toprow=$topres->fetch_assoc();
                $name=$toprow['name'];
            }
            $t_mark = $rankrow['finaltotal'];
            $t_duration = $rankrow['userdur'];
            $t_attended = $rankrow['c_ansd']+$rankrow['n_ansd'];
            $t_accuracy=round((($rankrow['c_corrt']+$rankrow['n_corrt'])/$t_attended)*100,2);
            $toppers.='<tr class="'.$mine_in.'">
            <td>'.$name.'</td>
            <td>'.$t_mark.'</td>
            <td>'.$t_duration.'</td>
            <td>'.$t_accuracy.' %</td>
            <td>'.$t_attended.'</td>
            </tr>';
            $t_avg_mark+=$t_mark;
            $t_avg_dur+=$t_duration;
            $t_avg_atnd+=$t_attended;
            $t_avg_accu+=$t_accuracy;
            $t_rank=$rank;
        }
    }
    $t_avg_mark=$t_avg_mark/$t_rank;
    $t_avg_dur=$t_avg_dur/$t_rank;
    $t_avg_atnd=$t_avg_atnd/$t_rank;
    $t_avg_accu=$t_avg_accu/$t_rank;
    $a_tot_mark=$a_tot_time=$a_count=0;
    $repsql="SELECT * FROM `report` WHERE `test_id`='$test_id'";
    $repres=$conn->query($repsql);
    while($reprow = $repres->fetch_assoc())
    {
        $a_mark=$reprow['finaltotal'];
        $a_tot_mark+=$a_mark;
        $a_time=$reprow['userdur'];
        $a_tot_time+=$a_time;
        if($a_mark>$my_score) {$rank++;}
        $a_count++;
    }
    $a_avg_mark = $a_tot_mark/$a_count;
    $a_avg_dur = $a_tot_time/$a_count;
    $a_avg_dur_m = floor($a_avg_dur/60);
    $a_avg_dur_s = floor($a_avg_dur%60);
    if($a_avg_dur_s<10) {$a_avg_dur_s="0".$a_avg_dur_s;}
    $a_avg_dur_t = $a_avg_dur_m.":".$a_avg_dur_s;
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
            <div class="p-3">
                <h1 class="h4 font-weight-bold text-info rounded"><?php echo $testname; ?></span></h1>
                <p class="text-secondary"><?php echo $sub_name; ?></p>
                <h3 class="text-danger font-weight-bold">My Rank: <?php echo $my_rank; ?></h3>
                <p><a href="/test-series/view-answer?id=<?php echo $test_id;?>" class="btn btn-outline-info">Show Answer</a></p>
            </div>
            <div class="row">  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-mortar-board border p-3"></span></p>
                    <p class="text-center font-weight-bold<?php if($my_score<0) {echo " text-danger";}?>"><?php echo $my_score." / ".$marks;?></p>
                    <p class="text-center"><?php echo round($perc_mark,2); ?>%</p>
                    <p class="text-center text-success pb-3">Your score</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-clock-o border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $my_time." / ".$actual_time;?></p>
                    <p class="text-center"> <?php echo round($perc_time,2);?>% </p>
                    <p class="text-center text-success pb-3">Time spend</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-child border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $crt_ans." / ".$atnd_ques;?></p>
                    <p class="text-center"><?php echo round($accuracy,2);?> % </p>
                    <p class="text-center text-success pb-3">Accuracy</p>
                    </div>
                </div>  
                <div class="col-sm-3">
                    <div class="bg-light">
                    <p class="text-center text-success pt-3"><span class="fa fa-bar-chart border p-3"></span></p>
                    <p class="text-center font-weight-bold"><?php echo $atnd_ques." / ".$total_ques;?></p>
                    <p class="text-center"><?php echo round($perc_ques,2);?> % </p>
                    <p class="text-center text-success pb-3">Question attended</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 my-1">
                    <div class="card p-2">
                        <div id="sp_score"></div>  
                    </div> 
                </div> 
                <div class="col-12 col-md-6 my-1">
                    <div class="card p-2">
                        <div id="sp_time"></div>  
                    </div> 
                </div> 
            </div>
            <div class="row">
                <div class="col-12 col-md-6 my-1">
                    <div class="card p-2">
                        <div id="sp_accur"></div>  
                    </div> 
                </div> 
                <div class="col-12 col-md-6 my-1">
                    <div class="card p-2">
                        <div id="sp_quest"></div>  
                    </div> 
                </div> 
            </div> 
            <br>
            <table class="table table-dark table-bordered table-hover table-striped">
                <tr>
                    <th>Position</th>
                    <th>Marks</th>
                    <th>Time</th>
                    <th>Accuracy</th>
                    <th>Attended Ques</th>
                </tr>
                <?php echo $toppers; ?>
            </table>
            <br>
            <br>
            <h5 class="text-success">Comparision of Marks</h5>
            <div id="compar_mark"></div>
            <br>
            <br>
            <h5 class="text-success">Comparision of Time</h5>
            <div id="compar_time"></div>
            <br><br>
            <br><br>
    </div> 
</div>    
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(sp_score);
    function sp_score() {
    valx = <?php echo $c_mark+$n_mark+$m_mark; ?>;
    valy = <?php echo $c_negmk+$n_negmk+$m_negmk; ?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['Positive Marks', valx],
        ['Negative Marks', valy]
    ]);
    var options = {
        is3D: true,
        title: 'Score',
        pieSliceText: 'value',
        backgroundColor : 'transparent',
    };
    var chart = new google.visualization.PieChart(document.getElementById('sp_score'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(sp_time);
    function sp_time() {
    valx = <?php echo $my_time;?>;
    valy = <?php if($my_time>$actual_time) {echo "0";} else {echo $actual_time-$my_time;} ?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['Time Taken (s)', valx],
        ['Time Remain (s)', valy]
    ]);
    var options = {
        is3D: true,
        title: 'Time',
        pieSliceText: 'value',
        backgroundColor : 'transparent',
    };
    var chart = new google.visualization.PieChart(document.getElementById('sp_time'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(sp_accur);
    function sp_accur() {
    valx = <?php echo abs($crt_ans);?>;
    valy = <?php echo $atnd_ques-abs($crt_ans);?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['Correct', valx],
        ['Wrong', valy]
    ]);
    var options = {
        is3D: true,
        title: 'Accuracy',
        pieSliceText: 'value',
        backgroundColor : 'transparent',
    };
    var chart = new google.visualization.PieChart(document.getElementById('sp_accur'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(sp_quest);
    function sp_quest() {
    valx = <?php echo $atnd_ques;?>;
    valy = <?php echo $total_ques-$atnd_ques;?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Slices');
    data.addRows([
        ['Attended', valx],
        ['Unattended', valy]
    ]);
    var options = {
        is3D: true,
        title: 'Attended',
        pieSliceText: 'value',
        backgroundColor : 'transparent',
    };
    var chart = new google.visualization.PieChart(document.getElementById('sp_quest'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(compar_mark);
    function compar_mark() {
    valx = <?php echo $my_score;?>;
    valy = <?php echo abs($t_avg_mark);?>;
    valz = <?php echo abs($a_avg_mark);?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Marks');
    data.addRows([
        ['My score', valx],
        ['Toppers', valy],
        ['All', valz]
    ]);
    var options = {
        is3D: true
    };
    var chart = new google.visualization.BarChart(document.getElementById('compar_mark'));
    chart.draw(data, options);
    }
    google.charts.setOnLoadCallback(compar_time);
    function compar_time() {
    valx = <?php echo abs($my_time);?>;
    valy = <?php echo abs($t_avg_dur);?>;
    valz = <?php echo abs($a_avg_dur);?>;
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Topping');
    data.addColumn('number', 'Seconds');
    data.addRows([
        ['My Duration', valx],
        ['Toppers', valy],
        ['All', valz]
    ]);
    var options = {
        is3D: true
    };
    var chart = new google.visualization.BarChart(document.getElementById('compar_time'));
    chart.draw(data, options);
    }
</script>
</body>    
</html>