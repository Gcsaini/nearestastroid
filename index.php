
<?php
include('function.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST['submit'])){

    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
   // echo $startdate;
    if($startdate==""){
        $error = "Please select start date";
    }elseif($enddate==""){
        $error = "Please select end date";
    }else{

        $s_date = changedateformat($startdate);
        $e_date = changedateformat($enddate);
        $numberofday = calculatenumberofdays($e_date, $s_date);
        if($numberofday>8){
            $error = "Date range can't be more than 8 days";
            
        }else{

            $response =  callApi($s_date,$e_date);
            $result = $response['element_count']; 
            if($result!=0){

                
                $str="";
                $arrnew = array();
                $temparr = array();
                $max=0;
                $min=999999.007878;
                $avg;
                $totala = $response['element_count'];
                $avg=0;
                for($i=0; $i<=$numberofday; $i++){
                    $repeat = strtotime("$i day",strtotime($startdate));
                    $newdate = date('Y-m-d',$repeat);
                    $count = count($response['near_earth_objects'][$newdate]);
                    $temparr[$i] = ["label"=>$newdate,"y"=>$count];
                
                 
                    for($j=0;$j<$count;$j++){
                        $vel = $response['near_earth_objects'][$newdate][$j]['close_approach_data'][0]['relative_velocity']['kilometers_per_second'];
                        $near = $response['near_earth_objects'][$newdate][$j]['close_approach_data'][0]['miss_distance']['astronomical'];
                        $avg+=$response['near_earth_objects'][$newdate][$j]['estimated_diameter']['kilometers']['estimated_diameter_max'];
                        $avg = $avg/$totala;
                        //$str.=$response['near_earth_objects'][$newdate][$j]['close_approach_data'][0]['relative_velocity']['kilometers_per_second'].",";
                        //$aid = $response['near_earth_objects'][$newdate][$j]['id'];
                        //$aname = $response['near_earth_objects'][$newdate][$j]['name'];
                    // array_push($arrnew, $vel);
                        if($vel>$max){
                            $faid = $response['near_earth_objects'][$newdate][$j]['id'];
                            $max=$vel;
                            
                        }
                        if($near<$min){
                            $min = $near;
                            $nearid = $response['near_earth_objects'][$newdate][$j]['id'];
                        }
                        
                    }
                }
            }
        }
    
    }
    
}

?>
<!DOCTYPE HTML>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<link href= "http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" 
        rel="Stylesheet"
        type="text/css" />
  
    <link href= "style.css" 
        rel="Stylesheet"
        type="text/css" />

    <script type="text/javascript" 
        src="http://code.jquery.com/jquery-1.7.2.min.js">
    </script>
  
    <script type="text/javascript" 
        src="http://code.jquery.com/ui/1.10.4/jquery-ui.js">
    </script>
    <style>
    
   
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <h1 class="text-center mb-4 mt-4">Find the Nearest object</h1>
            <div class="col-md-8 offset-md-2">
           

                <!-- Form code begins -->
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- Date input -->
                                <label class="control-label" for="date" >Start Date</label>
                                <input class="form-control" name="startdate" id="txtdate"  placeholder="MM/DD/YYY" type="date" />
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <!-- Date input -->
                                <label class="control-label" for="date">End Date</label>
                                <input class="form-control" id="date" name="enddate" placeholder="MM/DD/YYY" type="date" />
                            </div>
                            
                        </div>
                    </div>
                   
                    <div class="form-group text-center">
                        <span class="text-danger"><?php if(isset($error))echo $error;?>
                         
                    </div>

                    <div class="form-group text-center">
                        <!-- Submit button -->
                        <button class="btn btn-primary mt-4 sub-btn" name="submit" type="submit">Submit</button>
                    </div>
                </form>
                <!-- Form code ends -->

            </div>
        </div>
    </div>
</br>
</br>
    <?php 

    if(isset($totala)){
        ?>
    <div class="container">
    
                <div class="row">
                    <h4 class="text-center mb-4 mt-4">Selected date [<?php echo $startdate." TO ".$enddate; ?>]</h4>
                
                    <div class="col-md-4 mt-2 mb-4">
                        <div class="box-details">
                                <div class="details">

                                    <span class="title">Speed</span>
                                    </br>
                                    <span class="double"><?php if(isset($max)) echo $max; ?></span>
                                    </br>
                                    <span>KM/S</span>
                                    </br>
                                    <span>ID: <?php  if(isset($faid)) echo $faid; ?></span>

                                </div>
                                </br>
                                <div class="footer">
                                    <span>Fastest Astroid</span>
                                </div>
                        </div>

                    </div>
                    <div class="col-md-4 mt-2 mb-4">
                        <div class="box-details-2">
                            <div class="details">

                                <span class="title">Distance</span>
                                </br>
                                <span class="double"><?php  if(isset($min)) echo $min; ?></span>
                                </br>
                                <span>KM/S</span>
                                </br>
                                <span>ID: <?php if(isset($nearid))  echo $nearid; ?></span>

                            </div>
                            
                            <div class="footer">
                                <span>Closest Asteroid</span>
                            </div>
                        </div>
                            
                    </div>
                    <div class="col-md-4 mt-2 mb-4">
                        <div class="box-details-3">
                            <div class="details">

                                <span class="title">Size</span>
                                </br>
                                <span class="double"><?php echo $avg; ?></span>
                                </br>
                                <span>KM</span>
                               

                            </div>
                            
                            <div class="footer">
                                <span>Average Size of the Asteroids </span>
                            </div>
                        </div>
                            
                    </div> 
                </div>
            <div class="col-md-12 mt-4">
                    <div id="chartContainer" style="height: 370px; width: 100%;"></div>

            </div>
       
    </div>
    </br>
    </br>

    <?php } ?>

    
  
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script>
    window.onload = function() {

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2", // "light1", "light2", "dark1", "dark2"
            title: {
                text: "Nearest Astroid objects"
            },
            axisY: {
                title: "Number of Astroids"
            },
            data: [{
                type: "column",
                dataPoints: <?php echo json_encode($temparr); ?>
            }]
        });
        chart.render();

    }
    </script>
    
    

</body>

</html>