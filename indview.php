<?php include("auth.php"); include('db.php');
date_default_timezone_set("Asia/Kolkata");
$i=0;
$sql='select distinct MACHINE_TABLE_ID from sensor where FIRM_TABLE_ID='.$_SESSION['firm_table_id'].' and SENSOR_STATUS=1 ';
if(!!empty($_SESSION)){ session_start(); }
$_SESSION['pagename']='history';
 ?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
    

    <?php
include('header.php');
?>

<!-- (Optional) Latest compiled and minified JavaScript translation files -->
	</head>
<body  data-target="#my-navbar">
      <?php
      include('nav.php');
      ?>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>  
<br><br><br>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <center><h2>Cycle History</h2></center>
    <div class="container" id="x" style="border:0.25px solid #caced0;margin-top:10px;min-width:1350px; ">
    
    <form name='myform' method="post" action='indview.php'>
    <b>Machine Name: </b><select required onchange="selectsensor(this.value)" name='machine' id='machine'>
        <?php 
        if(!empty($_POST['machine'])){ 
        $msql='select DISTINCT MACHINE_NAME from sensor s, machine m where m.MACHINE_TABLE_ID=s.MACHINE_TABLE_ID and s.MACHINE_TABLE_ID= '.$_POST['machine'];
        $mnames=mysql_query($msql) or die($msql);
        while($row=mysql_fetch_array($mnames)){
        echo '<option selected value="'.$_POST['machine'].'">'.$row['MACHINE_NAME'].'</option>';
        }
        } else {
        ?>
        <option selected value=''>Machine Name</option>
        <?php
        }
        $msql='select m.MACHINE_TABLE_ID,m.MACHINE_NAME,s.SENSOR_TYPE_TABLE_ID from sensor s, machine m where m.MACHINE_TABLE_ID=s.MACHINE_TABLE_ID and s.FIRM_TABLE_ID= '.$_SESSION["firm_table_id"];
        
        
        $mnames=mysql_query($msql) or die($msql);
        $mstr='';
        while($row=mysql_fetch_array($mnames)){
        if($_POST['machine']==$row['MACHINE_TABLE_ID']){
        } else {
        $mstr.='<option value='.$row["MACHINE_TABLE_ID"].'>'.$row["MACHINE_NAME"].'</option>';
        }
        }
        echo $mstr.'</select>';
        if(!empty($_POST['date1'])){
        echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>Select Date From: </b><input name='date1' id='date1' class='js-example-basic-multiple' type='date' data-date-inline-picker='true' style='margin-top:1px;' value='".$_POST['date1']."' /></input>&nbsp;&nbsp;&nbsp;&nbsp;<b>To: </b><input name='date2' class='js-example-basic-multiple' id='date2' type='date' value='".$_POST['date2']."' data-date-inline-picker='true' style='margin-top:1px;'  /></input>";
        } else {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>Select Date From: </b><input name='date1' id='date1' type='date' data-date-inline-picker='true' class='js-example-basic-multiple' style='margin-top:1px;' /></input>&nbsp;&nbsp;&nbsp;&nbsp;<b>To: </b><input name='date2' class='js-example-basic-multiple' id='date2' type='date' data-date-inline-picker='true' style='margin-top:1px;'  /></input>";
        }
        if(($_POST['sensor']!="")){
        $query='select distinct DISPLAY_NAME,SENSOR_TABLE_ID from sensor where MACHINE_TABLE_ID='.$_POST['machine'].' and SENSOR_STATUS=1';
        $result=mysql_query($query) or die($query);
    	$select.='<span id="txtHint">&nbsp;&nbsp;&nbsp;&nbsp;<b>Select Sensor10: </b>
    	<select name="sensor[]" style="width: 15%" id="sensor" class="js-example-basic-multiple" multiple="multiple">';
    
	while($row=mysql_fetch_array($result)){
	   if(in_array($row['SENSOR_TABLE_ID'], $_POST['sensor'])){
	    $select.="<option value=".$row['SENSOR_TABLE_ID']." selected>".$row['DISPLAY_NAME']."</option>";
	    } else {
	    $select.="<option value=".$row['SENSOR_TABLE_ID'].">".$row['DISPLAY_NAME']."</option>";
	    }
	}
	$select.='</select></span>';
	echo $select.'</span></span></span>';
        //echo '<span id="txtHint"><b>Select Sensor: </b><select name="sensor" id="sensor" ><option selected >Select Sensor</option></select></span>';
         } 
         else if(($_POST['machine']!="")){
          $query='select distinct DISPLAY_NAME,SENSOR_TABLE_ID from sensor where MACHINE_TABLE_ID='.$_POST['machine'].' and SENSOR_STATUS=1';
        $result=mysql_query($query) or die($query);
        
         $select.='<span id="txtHint">&nbsp;&nbsp;&nbsp;&nbsp;<b>Select Sensor1: </b><select name="sensor[]" style="width: 15%" id="sensor" class="js-example-basic-multiple" multiple="multiple">';
    
	while($row=mysql_fetch_array($result)){
	    
	    $select.="<option value=".$row['SENSOR_TABLE_ID'].">".$row['DISPLAY_NAME']."</option>";
	    
	}
	$select.='</select>';
	echo $select;
	echo '</span></span></span>';
         } else {
         echo '<span id="txtHint">&nbsp;&nbsp;&nbsp;&nbsp;<b>Select Sensor3: </b><select name="sensor[]" id="sensor" style="width: 15%" class="js-example-basic-multiple" multiple="multiple"></select></span>';
         }
        ?>
    
        
        
        </select>
        </span>
        &nbsp;&nbsp;
        </span><input type = 'submit' name='submit' value = 'Search' style="padding: 8px 16px;font-size: 10px;">
        </form>
        <br>
       
    </div><br>
    
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>-->
 	<script type="text/javascript">
    $(document).ready(function() {
        //$('#sensor').multiselect();
         $('select').select2();
    });
</script>
    <script>
  
function selectsensor(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
    
       $.ajax({
				url: "getsensor.php?str="+str, 
				success: function(status1) {
				
				document.getElementById("txtHint").innerHTML = status1;
             var element = document.getElementById("#sensor");

             $('#sensor').select2();
				},
				cache: false
			});
        
            
}
}     
</script>

    <?php
    if(!empty($_POST['submit'])){
        $machine=$_POST['machine'];
    $date1=$_POST['date1'];
    $date2=$_POST['date2'];
    $cycle=$_POST['cycle'];
    $mno='';
    if($_POST['sensor']==''){
    $typesql='select distinct SENSOR_TYPE_TABLE_ID from sensor where MACHINE_TABLE_ID='.$machine;
    } else {
    $wxy=0;
    $sensor_string='';
                                
                                    foreach($_POST['sensor'] as $se){
                                    if($wxy==0){
                                    $sensor_string.='('.$se;
                                     $wxy++;
                                     
                                    } else {
                                    $sensor_string.= ','.$se;
                                    }
                                    
                                   
                                   
                                    }
                                    $sensor_string.=')';
                                      
    $typesql='select distinct SENSOR_TYPE_TABLE_ID from sensor where MACHINE_TABLE_ID='.$machine.' and SENSOR_TABLE_ID in '.$sensor_string;
    }
    $typeresult=mysql_query($typesql) or die($typesql);
    $setsql='SELECT DURATION,CURRENT_CYCLE_TABLE_ID FROM `current_cycle_information` WHERE MACHINE_TABLE_ID='.$machine.' and CURRENT_CYCLE_STATUS=1';
    $setresult=mysql_query($setsql) or die($setsql);
    while($rowset=mysql_fetch_array($setresult)){
    	$running_cycle_id=$rowset['CURRENT_CYCLE_TABLE_ID'];
    	
    	$duration=$rowset['DURATION'];
    	
    }
   /*  $setsql='SELECT * FROM `CYCLE_SETTINGS` WHERE RUNNING_CYCLE_ID='.$cycle;
    $setresult=mysql_query($setsql) or die($setsql);
    while($rowset=mysql_fetch_array($setresult)){
    	$type_id=$rowset['SENSOR_TYPE_TABLE_ID'];
    	$con[$type_id]['set']=$rowset['SET_PARAMETER'];
    	$con[$type_id]['safety']=$rowset['SAFETY_PARAMETER'];
    	$con[$type_id]['threshold']=$rowset['THRESHOLD'];
    }
    
    // Commented start
        $sql='select set_temp from current_cycle_information where MACHINE_TABLE_ID='.$machine;
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result)){
        $set_temp=$row['set_temp'];
        }
        */
       if(!empty($_POST['date1'])&&!empty($_POST['date2'])){
            $sql= 'select distinct CUR_TIME,SENSOR_HUMAN_READABLE_FORMAT from history_inf where   DATE(CUR_TIME)>=STR_TO_DATE(  "'.$date1.'",  "%Y-%m-%d") and DATE(CUR_TIME)<=STR_TO_DATE(  "'.$date2.'",  "%Y-%m-%d")  order by CUR_TIME DESC';
    } else if(!!empty($_POST['date1'])&&!empty($_POST['date2'])){
     $sql= 'select distinct CUR_TIME,SENSOR_HUMAN_READABLE_FORMAT from history_inf where   DATE(CUR_TIME)<=STR_TO_DATE(  "'.$date2.'",  "%Y-%m-%d")  order by CUR_TIME DESC';
    } else if(!empty($_POST['date1'])&&!!empty($_POST['date2'])){
     $sql= 'select distinct CUR_TIME,SENSOR_HUMAN_READABLE_FORMAT from history_inf where   DATE(CUR_TIME)>=STR_TO_DATE(  "'.$date1.'",  "%Y-%m-%d")  order by CUR_TIME DESC';
    } else if(!!empty($_POST['date1'])&&!!empty($_POST['date2'])){
     $sql= 'select distinct CUR_TIME,SENSOR_HUMAN_READABLE_FORMAT from history_inf   order by CUR_TIME DESC';
    }
    $sensorid=mysql_query($sql) or die($sql);
    //echo $sql;
        if(mysql_num_rows($sensorid)==0){
            echo '<center><h1>No data  to display </h1></center>';
        } else {
        echo "<div class='container' style='border:0.25px solid #caced0; min-width:1350px; hight:1000px;'>
            <h4 style='margin-left:0%;'> &nbsp;<span  ><button  id='export' class='button button2 pull-right ' style='margin-right:    10px;'>
  Data Export
            </button></span>
            </h4>";
            
    $xyz='select MACHINE_NAME from machine where MACHINE_TABLE_ID='.$_POST['machine'];
    $machinename=mysql_query($xyz) or die($xyz);
    while($mljjkl= mysql_fetch_array($machinename)){
        $machname=$mljjkl['MACHINE_NAME'];
    }
   
    $g=0;
     while($typerow=mysql_fetch_array($typeresult)){
    $x=$typerow['SENSOR_TYPE_TABLE_ID'];
  
     if(empty($_POST['sensor'])){
                                  $query='select * from sensor where MACHINE_TABLE_ID='.$_POST['machine'].' and SENSOR_TYPE_TABLE_ID='.$x.' and SENSOR_STATUS=1 order by SENSOR_TABLE_ID  ';
                                    } else{
                                    $wxy=0;
                                    $sensor_string='';
                                    foreach($_POST['sensor'] as $se){
                                     if($wxy==0){
                                    $sensor_string.='('.$se;
                                     $wxy++;
                                     
                                    } else {
                                    $sensor_string.= ','.$se;
                                    }
                                    }
                                    $sensor_string.=')';
                                        
                                       
                                        $query='select SENSOR_TABLE_ID , SENSOR_HUMAN_READABLE_FORMAT ,DISPLAY_NAME from  sensor where MACHINE_TABLE_ID='.$_POST['machine'].' and  SENSOR_TABLE_ID in '.$sensor_string.' and SENSOR_TYPE_TABLE_ID='.$x;
                                    }
                                   
                                    $result2=mysql_query($query) or die($query);
                                    $n=0;
                                    // echo $query;
                                    while($row1=mysql_fetch_array($result2)){
                                        $sensors[$g][]=$row1['SENSOR_TABLE_ID'];
                                        $name='SELECT MACHINE_TABLE_ID from sensor where SENSOR_TABLE_ID='.$row1['SENSOR_TABLE_ID'];
                                        $name2=mysql_query($name);
                                        while($namerow=mysql_fetch_array($name2)){
                                            $name4='select MACHINE_NAME from machine where MACHINE_TABLE_ID='.$namerow['MACHINE_TABLE_ID'];
                                            $name5=mysql_query($name4);
                                            while($namerow2=mysql_fetch_array($name5)){
                                                $machinename=$namerow2['MACHINE_NAME'];
                                            }
                                        }
                                        $sensorname[]=$row1['DISPLAY_NAME'];
                                        $sensornames[$g][]=$row1['DISPLAY_NAME'];
                                        $n++;
                                        $rep=array('_');
             $name = str_replace($rep,' ',$row1['DISPLAY_NAME']);
                                        $mno.='<th >'.$name.'</th>';
                                    }
                                    for($p=0;$p<$n;$p++){
            
             $ash=$sensors[$g][$p];
            
            $graphsql="select * from history_inf where  SENSOR_TABLE_ID= '$ash' and DATE(CUR_TIME)>=STR_TO_DATE(  '".$date1."',  '%Y-%m-%d') and DATE(CUR_TIME)<=STR_TO_DATE(  '".$date2."',  '%Y-%m-%d')" ;
            $graphsqlexec=mysql_query("$graphsql") or die($graphsql);
            while($row1=mysql_fetch_array($graphsqlexec)){
             $rep=array('-',':',' ');
             $Time = str_replace($rep,',',$row1['CUR_TIME']);
             $sub=substr($Time,5,2);
		$sub--;
	     $Time=substr_replace($Time,$sub,5,2); 
             $TempData = $row1['TEMPERATURE'];
             
             if(empty($Data[$g][$p])){
             $Data[$g][$p] ='[Date.UTC('.$Time.'),'.$TempData.']';
            
             } else {
                 $Data[$g][$p] .=',[Date.UTC('.$Time.'),'.$TempData.']';
             }
             
        }
        }
         $measuresql='select * from sensor_type where SENSOR_TYPE_TABLE_ID='.$typerow['SENSOR_TYPE_TABLE_ID'];             
                   $mresult=mysql_query($measuresql) or die($measuresql);
                   while($mrow=mysql_fetch_array($mresult)){
                   $measure_symbol[$g]=$mrow['MEASURE_SYMBOL'];
                   $type_name[$g]=$mrow['SENSOR_TYPE_NAME'];
                   }
                   
    ?>
    
   <div class="row-fluid" id="trend-graph">
    
        
		<!--				
    
        <!--</script>
						<style type="text/css">
							${demo.css}
						</style>
						<script type="text/javascript" > //alert('0');
							/*var mydString = "<?php //echo $_SESSION['sdate']; ?>"
							var arrayd = new Array();
							arrayd = mydString.split('-');
							
							var mytString = "<?php //echo $_SESSION['stime']; ?>"
							var arrayt = new Array();
							arrayt = mytString.split(':');
							$.noConflict(); */
							
                           jQuery( document ).ready(function() {
                                
								chart<?php echo $g; ?> = new Highcharts.Chart({
										chart: {
					renderTo: 'containerx<?php echo $g; ?>',
					defaultSeriesType: 'spline',
					
					
				  },
									credits: false,
									title: {
										text: ''
									},
									subtitle: {
										text: ''
									},
									xAxis: {
										type: 'datetime',
										labels: {
											overflow: 'justify'
										},
										title: {
											text: 'Time'
										}
									},
									yAxis: {
                                            gridLineWidth: 1,
										title: {
											text: '<?php if(!!empty($type_name[$g])){ echo 'Not defined';} else {echo $type_name[$g];} ?>'
										},
                                        plotLines: [{
                    value: <?php 
                    if(!!empty($con[$x]['set'])){
								  echo '0';
								  } else {
								  echo $con[$x]['set'];
								  } ?>,
                    color: 'green',
                    dashStyle: 'shortdash',
                    width: 2,
                    label: {
                        text: 'Set <?php echo $type_name[$g]; ?>'
                    }
                },
                {
                    value: <?php 
                    if(!empty($con[$x]['set'])){
                    echo $con[$x]['threshold']+$con[$x]['set'];
                    } else{
                     echo '0';
                    }
                     ?>,
                    color: '#ffae59',
                    dashStyle: 'shortdash',
                    width: 1,
                    
                },
                {
                    value: <?php 
                    if(!empty($con[$x]['set'])){
                    echo $con[$x]['set']-$con[$x]['threshold'];
                    } else{
                     echo '0';
                    }
                     ?>,
                    color: '#ffae59',
                    dashStyle: 'shortdash',
                    width: 1,
                    
                }],
										minorGridLineWidth: 0,
										gridLineWidth: 0,
										alternateGridColor: null,
										
									},
									tooltip: {
										valueSuffix: '<?php 
										if($x!=1){
										echo $measure_symbol[$g];
										} else {
										echo "Â°C";
										} ?>'
									},
									plotOptions: {
										spline: {
											lineWidth: 2,
											states: {
												hover: {
													lineWidth: 4
												}
											},
											marker: {
												enabled: false
											},
											pointInterval: 5000, // 5 sec
											
											pointStart: Date.UTC(2016, 09, 30)
										}
									},
                                    navigator: {
        	adaptToUpdatedData: false
        },
									series: [
                                        <?php
                                        for($p=0;$p<$n;$p++){
                                            ?>
                                        {
										name: '<?php echo $sensornames[$g][$p]; ?> ',
										data: [ <?php echo $Data[$g][$p]; ?> ],
										 dataGrouping: {
                enabled: true,
                forced: true,
                units: [
                    ['day', [1]]
                ]
            },
										//color: 'red'
										/*color: {
											linearGradient: { x1: 0, x2: 0, y1: 0, y2: 1 },
											stops: [
												[0, '#ff0000'],
												[1, '#3366AA']
											]
									}
							*/
									},
                                        <?php } ?>
										],
									navigation: {
										menuItemStyle: {
											fontSize: '10px'
										}
									}
								});
							});
						</script>
      -->
        
            <!--<div class="panel panel-primary " style="border:0.25px solid #caced0;padding-left:0px;padding-right:0px;">
                <div class="panel-heading" style="height:39px;"><?php 
     
     echo '<b>'.$machname.': <span id="ctemp<?php echo $g; ?>"></span>';
                     
     echo '</b>';
            ?></div>
            <div id="containerx<?php echo $g; ?>" stylle="height:300px;"></div></div>-->
             <?php
    $g++;
    }
    
    ?>
    <?php
    $lsql='select * from firm where FIRM_TABLE_ID='.$_SESSION['firm_table_id'];
        $loc=mysql_query($lsql);
        while($location=mysql_fetch_array($loc)){
            $lon=$location['LONGITUDE'];
            $lat=$location['LATITUDE'];
        }
        $co= $lon.','.$lat;
        
        ?>
          <!--  <div class="panel panel-primary col-sm-5 col-lg-5 col-md-5" style=" border:0.25px solid #eeeeee ; margin-left:3%; margin-top:0%; padding-left:0px; padding-right:0px;width:550px;"><div class="panel-heading" style=""><b>Map View</b></div>
        <div id="map1" class="panel-body" style="height:395px; padding-left:0px;padding-top:0px;padding-bottom:0px;"  >
        </div></div>
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyDA9dfVJd38I-5P_JwwFVQh76t6PgUzog0"></script>

<script type="text/javascript">
    jQuery(function ($) {
        function init_map1() {
            var myLocation = new google.maps.LatLng(<?php echo $co; ?>);
            var mapOptions = {
                center: myLocation,
                zoom: 16
            };
            var marker = new google.maps.Marker({
                position: myLocation,
                title: "Property Location"
            });
            var map = new google.maps.Map(document.getElementById("map1"),
                mapOptions);
            marker.setMap(map);
        }
        init_map1();
    });
</script>

<style>
   /* .map {
        min-width: 300px;
        min-height: 400px;
        width: 45%;
        height: 45%;
        margin-left:5%;
        margin-top:0.5%;
    }
*/
    .header {
        background-color: #F5F5F5;
        color: #36A0FF;
        height: 60px;
        font-size: 27px;
        padding: 0px;
    }
</style>-->
        <div class="row-fluid temp-reading-tbl" id="temp-reading-tbl">
						
						<div class="table-reading" style="border:0px solid #caced0;padding-right:0px; hight: 10000px;" >
						<table cellpadding="1" cellspacing="1" id="users" class="display table  table-responsive table-striped scroll
                                      table2excel " width="100%" >
      <thead class="dt-head-right">
    <tr >
    <th >CYCLE ID</th>
        <th >TIME</th>
        <?php
                                   
                                    
                                    echo $mno;
                                    ?>
        
    </tr>
    </thead>
   
</table>
						</div>
					</div><?php
        
     ?>
  						<!--<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>-->
    <script src="//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="//cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css"/>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>  
<script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.html5.min.js"></script>
        <script type="text/javascript">
    $(document).ready(function () {
               refreshTable();
        

    }
    
    );
   
   function refreshTable(){
       var table= $('#users').DataTable(
        
        {
        //"info":     false,
        "order": [[ 1, 'desc' ]],
        "filter": false,
        //"bLengthChange": false,
            "columns": [
          {"data": "CURRENT_CYCLE_TABLE_ID"},
  		  
                {"data": "CUR_TIME"},
                
                <?php foreach($sensorname as $name){ ?>
                {"data": "<?php echo $name; ?>"},
                <?php } ?>
                
               
            ],
            buttons: [
            'excelHTML5'
        ],
            "columnDefs": [
    { className: "dt-head-center", "targets": "_all" }
  ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: 'demo3.php?<?php if(!empty($_POST['date1'])&&!empty($_POST['date2'])){
                echo '&date1='.$date1.'&date2='.$date2;
                } else if(!!empty($_POST['date1'])&&!empty($_POST['date2'])){
                echo '&date2='.$date2;
                } else if(!empty($_POST['date1'])&&!!empty($_POST['date2'])){
                echo '&date1='.$date1;
                } ?>&machine=<?php echo $_POST['machine'];
                if($_POST['sensor']!=''){
               
                echo '&sensor='.$sensor_string;
                }
                 ?>',
               
                type: 'POST'
            }
        });
   
        
         
        }  
        
        
    $(document).ready(function(){
    $('#export').click(function(){
         $.ajax({
				 url: 'demo3.php?<?php if(!empty($_POST['date1'])&&!empty($_POST['date2'])){
                echo '&date1='.$date1.'&date2='.$date2;
                } else if(!!empty($_POST['date1'])&&!empty($_POST['date2'])){
                echo '&date2='.$date2;
                } else if(!empty($_POST['date1'])&&!!empty($_POST['date2'])){
                echo '&date1='.$date1;
                } ?>&machine=<?php echo $_POST['machine'];
                if($_POST['sensor']!=''){
                echo '&sensor='.$sensor_string;
                }
                 ?>&cycle=<?php echo $cycle; ?>',
				success: function(points) {
				console.log('data' + points);
					var data = points;
				JSONToCSVConvertor(data, "Export Data", true);
				},
				cache: false
			});
        if(data == '')
            return;
        
        
    });
});

function JSONToCSVConvertor(JSONData, ReportTitle, ShowLabel) {
    //If JSONData is not an object then JSON.parse will parse the JSON string in an Object
    var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
    
    var CSV = '';    
    //Set Report title in first row or line
    
    CSV += ReportTitle + '\r\n\n';

    //This condition will generate the Label/Header
    if (ShowLabel) {
        var row = "";
        
        //This loop will extract the label from 1st index of on array
        for (var index in arrData[0]) {
            
            //Now convert each value to string and comma-seprated
            row += index + ',';
        }

        row = row.slice(0, -1);
        
        //append Label row with line break
        CSV += row + '\r\n';
    }
    
    //1st loop is to extract each row
    for (var i = 0; i < arrData.length; i++) {
        var row = "";
        
        //2nd loop will extract each column and convert it in string comma-seprated
        for (var index in arrData[i]) {
            row += '"' + arrData[i][index] + '",';
        }

        row.slice(0, row.length - 1);
        
        //add a line break after each row
        CSV += row + '\r\n';
    }

    if (CSV == '') {        
        alert("Invalid data");
        return;
    }   
    
    //Generate a file name
    var fileName = "MyReport_";
    //this will remove the blank-spaces from the title and replace it with an underscore
    fileName += ReportTitle.replace(/ /g,"_");   
    
    //Initialize file format you want csv or xls
    var uri = 'data:text/csv;charset=utf-8,' + escape(CSV);
    
    // Now the little tricky part.
    // you can use either>> window.open(uri);
    // but this will not work in some browsers
    // or you will not get the correct file extension    
    
    //this trick will generate a temp <a /> tag
    var link = document.createElement("a");    
    link.href = uri;
    
    //set the visibility hidden so it will not effect on your web-layout
    link.style = "visibility:hidden";
    link.download = fileName + ".csv";
    
    //this part will append the anchor tag and remove it after automatic click
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}    
          
</script>
    
						
       	<script>
            //document.getElementById('ctemp').style.fontWeight="bold";
            
            //document.getElementById('ctemp').innerHTML="<?php foreach($temp as $as){
       //  echo $as.'&degC ';
     }//echo $row['TEMPERATURE']; ?>";
                              //  document.getElementById('b').style.width ="<?php  echo $bat*0.22; ?>px";
                                //document.getElementById("b").style.color ="#000000";
                                //document.getElementById("b").style.fontSize ="12px";
                                //document.getElementById("b").style.fontWeight ="BOLD";
                               // document.getElementById('b').innerHTML="<?php //echo $bat.'%'; ?>";
                                </script>
								
            </div></div><br>
    <?php
    
    ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
						<script src="https://code.highcharts.com/modules/exporting.js"></script>
     <script src="src/jquery.table2excel.js"></script>
        					<script type="text/javascript">
                              /*  setTimeout(function(){
   window.location.reload(1);
}, 5000);*/
                
                               $(".target").click(function() {
				$(".table2excel").table2excel({
					exclude: ".noExl",
					name: "Excel Document Name",
					filename: "data",
					fileext: ".xls",
					exclude_img: true,
					exclude_links: true,
					exclude_inputs: true
				});
			});
                                
                                                 
                                                    
                                
</script>

  <!--  <script src="https://code.highcharts.com/highcharts.js"></script>
						<script src="https://code.highcharts.com/modules/exporting.js"></script>-->
   
<?php
   }
  echo  '<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>';  
    }
    ?>

<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>-->
    </body>
  