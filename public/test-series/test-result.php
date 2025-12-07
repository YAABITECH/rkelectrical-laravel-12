<?php
session_start();
//error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$tmark=$tnmark=$tpmark=0;
$user_id=$_COOKIE['id'];
if(!empty($_SESSION['t_starttime']))
{
    $startTime = $_SESSION['t_starttime'];
    $duration = $_SESSION['t_duration'];
    $my_durat = time()-$startTime;
    $timediff = ($duration*60)-($my_durat);
    $c_ansd=$n_ansd=$m_ansd=$c_notans=$n_notans=$m_notans=$c_corrt=$n_corrt=$m_corrt=$c_wrng=$n_wrng=$m_wrng=$c_mark=$n_mark=$m_mark=$c_negmk=$n_negmk=$m_negmk=$c_total=$n_total=$m_total=0;
    if(!empty($_POST['test_id']))
    {
        $test_id=$_POST['test_id'];
        $mysql="SELECT * FROM `test` WHERE `id`='$test_id'";
        $myres=$conn->query($mysql);
        $myrow = $myres->fetch_assoc();
        $testmark=$myrow['marks'];
        $testsql="SELECT * FROM `test_ques` WHERE `test_id`='$test_id'"; 
        $selres=$conn->query($testsql);
        while($selrow = $selres->fetch_assoc())
        {
            $num=$selrow['num'];
            $mark=$selrow['mark'];
            $mark=(float)$mark;
            $neg_mark=$selrow['neg_mark'];
            $neg_mark=(float)$neg_mark;
            $answer=$selrow['answer'];
            $answer1=$selrow['answer1'];
            $answer2=$selrow['answer2'];
            $mans1=$selrow['mans1'];
            $mans2=$selrow['mans2'];
            $mans3=$selrow['mans3'];
            $mans4=$selrow['mans4'];
            if($selrow["questype"]=="choice")
            {
                if(!empty($_POST["answer_".$num]))
                {
                    $c_ansd++;
                    if($_POST["answer_".$num]==$answer)
                    {
                        $c_corrt++;
                        $c_mark=$c_mark+$mark;
                    } else  
                    {
                        $c_wrng++;
                        $c_negmk=$c_negmk+$neg_mark;
                    }
                    $c_total=$c_mark-$c_negmk;
                } else
                {
                    $c_notans++;
                }
            }else if($selrow["questype"]=="multi")
            {
                if(isset($_POST["manswer_".$num."_1"]) || isset($_POST["manswer_".$num."_2"]) || isset($_POST["manswer_".$num."_3"]) || isset($_POST["manswer_".$num."_4"]))
                {
                    $m_ansd++;
                    if($_POST["manswer_".$num."_1"]==$mans1 && $_POST["manswer_".$num."_2"]==$mans2 && $_POST["manswer_".$num."_3"]==$mans3 && $_POST["manswer_".$num."_4"]==$mans4 )
                    {
                        $m_corrt++;
                        $m_mark=$m_mark+$mark;
                    } else  
                    {
                        $m_wrng++;
                        $m_negmk=$m_negmk+$neg_mark;
                    }
                    $m_total=$m_mark-$m_negmk;
                } else
                {
                    $m_notans++;
                }
            } else
            {
                if(!empty($_POST["answer_".$num]))
                {
                    $n_ansd++;
                    if((!empty($answer2) && $_POST["answer_".$num]>=$answer1 && $_POST["answer_".$num]<=$answer2) || (empty($answer2) && $_POST["answer_".$num]==$answer1))
                    {
                        $n_corrt++;
                        $n_mark=$n_mark+$mark;
                    } else
                    {
                        $n_wrng++;
                        $n_negmk=$n_negmk+$neg_mark;
                    }
                    $n_total=$n_mark-$n_negmk;
                } else
                {
                    $n_notans++;
                }
            }
        }
        $finaltotal=$c_total+$n_total+$m_total;
        $report="INSERT INTO `report` (`test_id`,`user_id`,`admindur`,`userdur`,`c_ansd`,`n_ansd`,`c_notans`,`n_notans`,`c_corrt`,`n_corrt`,`c_wrng`,`n_wrng`,`c_mark`,`n_mark`,`c_negmk`,`n_negmk`,`c_total`,`n_total`,`finaltotal`,`marks`,`m_ansd`,`m_notans`,`m_corrt`,`m_wrng`,`m_mark`,`m_negmk`,`m_total`) VALUES  ('$test_id','$user_id','$duration','$my_durat','$c_ansd','$n_ansd','$n_notans','$c_notans','$c_corrt','$n_corrt','$c_wrng','$n_wrng','$c_mark','$n_mark','$c_negmk','$n_negmk','$c_total','$n_total','$finaltotal','$testmark','$m_ansd','$m_notans','$m_corrt','$m_wrng','$m_mark','$m_negmk','$m_total')";
        $reportres=$conn->query($report);
        if($reportres==true)
        {
            echo "inserted";
            unset($_SESSION['t_starttime']);
            unset($_SESSION['t_duration']);
            unset($_SESSION['t_closed']);
            $error='';
        } else
        {
            $error='<p class="text-center text-danger">Something went wrong. Please refresh the page and resubmit.</p>';
            echo $error;
            echo "<br>".$conn->error;
        }
    }
} else
{
    echo '<center style="color:red;"><br><br><br><br><br>You can\'t resubmit exam<br><br><br><a href="/">Go home</a></center>';exit;
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
    <br>
    <br>
    <h4 class="text-center text-success">Your score&nbsp;:&nbsp;<?php echo $finaltotal; ?></h4>   
    <br>
    <br>
    <table class="table table-bordered table-hover">
        <thead>
            <tr class="bg-info">
                <th></th>
                <th>Number type</th>
                <th>Mcq</th>
                <th>Multichoice</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Answered</td>
                <td><?php echo $n_ansd; ?></td>
                <td><?php echo $c_ansd;?></td>
                <td><?php echo $m_ansd;?></td>
            </tr>
            <tr>
                <td>Not Answered</td>
                <td><?php echo $n_notans; ?></td>
                <td><?php echo $c_notans; ?></td>
                <td><?php echo $m_notans; ?></td>
            </tr>
            <tr>
                <td>Correct answer</td>
                <td><?php echo $n_corrt; ?></td>
                <td><?php echo $c_corrt; ?></td>
                <td><?php echo $m_corrt; ?></td>
            </tr>
            <tr>
                <td>Wrong Answer</td>
                <td><?php echo $n_wrng; ?></td>
                <td><?php echo $c_wrng; ?></td>
                <td><?php echo $m_wrng; ?></td>
            </tr>
            <tr class="text-success">
                <td>Plus mark</td>
                <td><?php echo $n_mark; ?></td>
                <td><?php echo $c_mark; ?></td>
                <td><?php echo $m_mark; ?></td>
            </tr>
            <tr class="text-danger">
                <td>Negative mark</td>
                <td><?php echo $n_negmk; ?></td>
                <td><?php echo $c_negmk; ?></td>
                <td><?php echo $m_negmk; ?></td>
            </tr>
            <tr class="bg-dark text-light">
                <td>Total Marks</td>
                <td><?php echo $n_total; ?></td>
                <td><?php echo $c_total; ?></td>
                <td><?php echo $m_total; ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <p class="text-center"><a href="/test-series/report?test_id=<?php echo $test_id;?>" class="btn btn-info">View Reports &amp; Analytics</a></p>
    <br><br>
</div>    
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
</body>    
