<?php 
	require_once 'lib/ApiHandler.php';
	$api=new ApiHandler();
	$histori=$api->History('2017-08-01','2017-08-27',$_GET['id'],'');
	if ($histori['status']=='failed') {
		$history[]=array('TransactionType'=>'No History','TransactionDate'=>' this time','Amount'=>'0.00','CurrentBalance'=>'0.00');
	}else{
		$histori=$histori['response'];
		foreach ($histori->getTransactionDetails() as $key => $data) {
			$history[$key]['TransactionID']=$data->getTransactionID();
		    $history[$key]['AccountStatementID']=$data->getAccountStatementID();
		    $history[$key]['TransactionDate']=$data->getTransactionDate();
		    $history[$key]['TransactionType']=$data->getTransactionType();
		    $history[$key]['Amount']=$data->getAmount();
		    $history[$key]['CurrencyCode']=$data->getCurrencyCode();
		    $history[$key]['Description']=$data->getDescription();
		    $history[$key]['CurrentBalance']=$data->getCurrentBalance();
		}
	}
 ?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>E-Celengan</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    E-Celengan
                </a>
            </div>

            <ul class="nav">
                <li>
                    <a href="dashboard.php">
                        <i class="pe-7s-home"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li>
                    <a href="transfer.php">
                        <i class="pe-7s-repeat"></i>
                        <p>Transfer</p>
                    </a>
                </li>
                <li>
                    <a href="topup.php">
                        <i class="pe-7s-gleam"></i>
                        <p>TopUp</p>
                    </a>
                </li>
                <li>
                    <a href="withdraw.php">
                        <i class="pe-7s-shopbag"></i>
                        <p>Withdraw</p>
                    </a>
                </li>
                <li>
                    <a href="wallets.php">
                        <i class="pe-7s-wallet"></i>
                        <p>Wallets</p>
                    </a>
                </li>
                
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand">
                        Josi Aranda
                    </div>
                </div>
                <div class="collapse navbar-collapse">
                    

                    <ul class="nav navbar-nav navbar-right">
                        
                        <li>
                            <a href="#">
                                <p>Log out</p>
                            </a>
                        </li>
						<li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?php 
                    foreach ($history as $key => $value) {
                    	?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title"><?php echo "$value[TransactionType]"; ?></h4>
                                    <p class="category"> at <?php echo "$value[TransactionDate]"; ?> </p>
                                </div>
                                <div class="content">
                                    <div>
                                    	
                                        <h2 class="text-info" style="font-size: 32px;">Amount: <?php echo "$value[Amount]"; ?></h2>
                                        <h3 class="text-success" style="font-size: 14px;"> <?php echo "Current Balance:<br/> IDR $value[CurrentBalance]"; ?></h3>
                                    </div>

                                    <div class="footer">
                                        
                                        <hr>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                     ?>

                    
                </div>
                


                
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> <a href="#">Hexagrit</a>, made with sweats and tears.
                </p>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

	<!--  Checkbox, Radio & Switch Plugins -->
	<script src="assets/js/bootstrap-checkbox-radio-switch.js"></script>

	<!--  Charts Plugin -->
	<script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="assets/js/light-bootstrap-dashboard.js"></script>

	<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
	<script src="assets/js/demo.js"></script>

	

</html>
