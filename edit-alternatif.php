<?php
	session_start();
	include('configdb.php');
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $_SESSION['judul']." - ".$_SESSION['by'];?></title>
	
    <!-- Bootstrap core CSS -->
    <!--link href="ui/css/bootstrap.css" rel="stylesheet"-->
	<link href="ui/css/cerulean.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="ui/css/jumbotron.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--script src="./index_files/ie-emulation-modes-warning.js"></script-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  
  <body>

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $_SESSION['judul'];?></a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="index.php">Home</a></li>
              <li><a href="kriteria.php">Data Kriteria</a></li>
              <li><a href="alternatif.php">Data Alternatif</a></li>
			  <li><a href="analisa.php">Analisa</a></li>
              <li><a href="perhitungan.php">Perhitungan</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
		<div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <div class="panel-heading">Edit Data Alternatif</div>
						<?php
											$kriteria = $mysqli->query("select * from kriteria");
											if(!$kriteria){
												echo $mysqli->connect_errno." - ".$mysqli->connect_error;
												exit();
											}
											$i=0;
											while ($row = $kriteria->fetch_assoc()) {
												@$k[$i] = $row["kriteria"];
												$i++;
											}
									$result = $mysqli->query("select * from alternatif where id_alternatif = ".$_GET['id']."");
									if(!$result){
										echo $mysqli->connect_errno." - ".$mysqli->connect_error;
										exit();
									}
									while($row = $result->fetch_assoc()){
						?>
		  <div class="panel-body">
							<form role="form" method="post" action="edit.php?id=<?php echo $_GET['id'];?>">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="alternatif">Alternatif</label>
                                            <input type="text" class="form-control" name="alternatif" id="alternatif" value="<?php echo $row["alternatif"];?>" placeholder="Nama Karakter">
                                        </div>
                    <div class="form-group">
                                            <label for="k1"><?php echo ucwords($k[0]);?></label>
                                            <input type="number" class="form-control" name="k1" id="k1" placeholder="Nilai Ketahanan Serangan Sihir Karakter" value="<?php echo $row["k1"]; ?>" min="0" max="999" step="1">

                                        </div>
										<div class="form-group">
                                            <label for="k2"><?php echo ucwords($k[1]);?></label>
                                            <input type="number" class="form-control" name="k2" id="k2" placeholder="Nilai Ketahanan Serangan Fisik Karakter" value="<?php echo $row["k2"]; ?>" min="0" max="999" step="1">

                                        </div>
										<div class="form-group">
                                           <label for="k3"><?php echo ucwords($k[2]);?></label>
                                           <input type="number" class="form-control" name="k3" id="k3" placeholder="Nilai Kecepatan Perpindahan Karakter" value="<?php echo $row["k3"]; ?>" min="0" max="999" step="1">

                                        </div>
										<div class="form-group">
                                           <label for="k4"><?php echo ucwords($k[3]);?></label>
                                           <input type="number" class="form-control" name="k4" id="k4" placeholder="Nilai Besaran Darah Karakter" value="<?php echo $row["k4"]; ?>" min="0" max="999" step="1">

                                        </div>
										<div class="form-group">
                                           <label for="k5"><?php echo ucwords($k[4]);?></label>
                                           <input type="number" class="form-control" name="k5" id="k5" placeholder="Nilai Kecepatan Regenerasi Darah Karakter" value="<?php echo $row["k5"]; ?>" min="0" max="999" step="1">
                                       
                                        </div>
                    <div class="form-group">
                                            <label for="k6"><?php echo ucwords($k[5]);?></label>
                                            <input type="number" class="form-control" name="k6" id="k6" placeholder="Nilai Besaran Mana/Energi Karakter" value="<?php echo $row["k6"]; ?>" min="0" max="999" step="1">

                                        </div>
										<div class="form-group">
                                            <label for="k7"><?php echo ucwords($k[6]);?></label>
                                            <input type="number" class="form-control" name="k7" id="k7" placeholder="Nilai Kecepatan Regenerasi Mana/Energi Karakter" value="<?php echo $row["k7"]; ?>" min="0" max="999" step="1">

                                        </div>
                    <div class="form-group">
                                            <label for="k8"><?php echo ucwords($k[7]);?></label>
                                            <input type="number" class="form-control" name="k8" id="k8" placeholder="Nilai Kekuatan Serangan Sihir Karakter" value="<?php echo $row["k8"]; ?>" min="0" max="999" step="1">
                                            
                                        </div>
                    <div class="form-group">
                                            <label for="k9"><?php echo ucwords($k[8]);?></label>
                                            <input type="number" class="form-control" name="k9" id="k9" placeholder="Nilai Kekuatan Serangan Fisik Karakter" value="<?php echo $row["k9"]; ?>" min="0" max="999" step="1">
                                        
                                        </div>
                    <div class="form-group
                                            <label for="k10"><?php echo ucwords($k[9]);?></label>
                                            <input type="number" class="form-control" name="k10" id="k10" placeholder="Nilai Kekuatan Seangan Kritis Karakter" value="<?php echo $row["k10"]; ?>" min="0" max="999" step="1">
                                                                                
                                        </div>
                    <div class="form-group
                                            <label for="k11"><?php echo ucwords($k[10]);?></label>
                                            <input type="number" class="form-control" name="k11" id="k11" placeholder="Nilai Kecepatan Serangan Karakter" value="<?php echo $row["k11"]; ?>" min="0" max="999" step="1">
                                                                                                    
                                        </div>
											</select>
                                        </div>
                                    </div><!-- /.box-body -->

                                    <div class="box-footer">
                                            <button type="reset" class="btn btn-info">Reset</button>
                                          <a href="alternatif.php" type="cancel" class="btn btn-warning">Batal</a>
                                        <button type="submit" class="btn btn-primary">Proses Edit</button>
                                    </div>
                            </form>
							<?php
									}
							?>
		    </div>
		  <div class="panel-footer text-primary"><?php echo $_SESSION['by'];?><div class="pull-right"></div></div>
		</div>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="ui/js/jquery-1.10.2.min.js"></script>
	<script src="ui/js/bootstrap.min.js"></script>
	<script src="ui/js/bootswatch.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="ui/js/ie10-viewport-bug-workaround.js"></script>

</body></html>