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
              <li class="active"><a href="#">Perhitungan</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
		<div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <div class="panel-heading">Matrix Alternatif-Kriteria</div>
		  <div class="panel-body">
			<center>
				<?php

				// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Tabel Matrix Alternatif - Kriteria <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //

				$alt = get_alternatif();
				$alt_name = get_alt_name();
				end($alt_name); $arl2 = key($alt_name)+1; //new
				$kep = get_kepentingan();
				$cb = get_costbenefit();
				$k = jml_kriteria();
				$a = jml_alternatif();
				$tkep = 0;
				$tbkep = 0;

					// Pagination 
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$limit = 10; 
					$offset = ($page - 1) * $limit; 
					$total_data = count($alt); 
					$total_pages = ceil($total_data / $limit); 

					// Ambil hanya data sesuai halaman
					$alt_paginated = array_slice($alt, $offset, $limit);
					$alt_name_paginated = array_slice($alt_name, $offset, $limit);

					echo "<table class='table table-bordered table-hover text-center'>";
					echo "<thead class='thead-dark'><tr><th>Alternatif / Kriteria</th>";
					for ($i = 1; $i <= $k; $i++) {
						echo "<th>K{$i}</th>";
					}
					echo "</tr></thead><tbody>";

					foreach ($alt_paginated as $index => $row) {
						echo "<tr><td><b>A" . ($offset + $index + 1) . "</b></td>";
						foreach ($row as $value) {
							echo "<td>{$value}</td>";
						}
						echo "</tr>";
					}
					echo "</tbody></table>";

					// Navigasi Halaman
					echo '<nav aria-label="Page navigation">';
					echo '<ul class="pagination justify-content-center">';

					// Tombol "Sebelumnya"
					if ($page > 1) {
						echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '">Sebelumnya</a></li>';
					}

					// Nomor Halaman
					for ($i = 1; $i <= $total_pages; $i++) {
						$active = ($i == $page) ? 'active' : '';
						echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
					}

					// Tombol "Selanjutnya"
					if ($page < $total_pages) {
						echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '">Selanjutnya</a></li>';
					}

					echo "</ul>";
					echo "</nav>";
					echo "</div></div>";
					
					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> bobot/nilai kepentingan <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Perhitungan Bobot Kepentingan</b></div>";
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<thead><tr><th></th><th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th><th>K6</th><th>K7</th><th>K8</th><th>K9</th><th>K10</th><th>K11</th><th>Jumlah</th></tr></thead>";
					
					echo "<tr><td><b>Kepentingan</b></td>"; 
						for($i=0;$i<$k;$i++){ // looping sebanyak kriteria
							$tkep = $tkep + $kep[$i]; // total kepentingan
							echo "<td>".$kep[$i]."</td>";
						}
						echo "<td>".$tkep."</td></tr>";
						echo "<tr><td><b>Bobot Kepentingan</b></td>";
						for($i=0;$i<$k;$i++){ // looping sebanyak kriteria
							$bkep[$i] = ($kep[$i]/$tkep); // kepentingan dibagi total kepentingan
							$tbkep = $tbkep + $bkep[$i]; // total bobot kepentingan
							echo "<td>".round($bkep[$i],6)."</td>";
						}
						echo "<td>".$tbkep."</td></tr>";
					echo "</table>";
					echo "</div></div>";
					
					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> pangkat <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Perhitungan Pangkat</b></div>";
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<thead><tr><th></th><th>K1</th><th>K2</th><th>K3</th><th>K4</th><th>K5</th><th>K6</th><th>K7</th><th>K8</th><th>K9</th><th>K10</th><th>K11</th></tr></thead>";
						echo "<tr><td><b>Cost/Benefit</b></td>";
						for($i=0;$i<$k;$i++){ // looping sebanyak kriteria
							echo "<td>".ucwords($cb[$i])."</td>"; 
						}
						echo "</tr>";
						echo "<tr><td><b>Pangkat</b></td>";
						for($i=0;$i<$k;$i++){ // looping sebanyak kriteria
							if($cb[$i]=="cost"){ // informasi jika cost
								$pangkat[$i] = (-1) * $bkep[$i]; // pangkat negatif
								echo "<td>".round($pangkat[$i],6)."</td>";
							}
							else{
								$pangkat[$i] = $bkep[$i]; // pangkat positif
								echo "<td>".round($pangkat[$i],6)."</td>"; 
							}
						}
						echo "</tr>";
					echo "</table>";
					echo "</div></div>";

					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> vektor S <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					$ss = []; // Inisialisasi array untuk menyimpan hasil perhitungan vektor S
					for ($i = 0; $i < count($alt); $i++) { // Looping untuk setiap alternatif
						$prod = 1; // variabel untuk menyimpan hasil perkalian
						for ($j = 0; $j < $k; $j++) { // Looping kedua untuk setiap kriteria
							$prod *= pow($alt[$i][$j], $pangkat[$j]); // Menghitung hasil pangkat dari setiap alternatif
						}
						$ss[$i] = $prod;  // Menyimpan hasil perkalian ke dalam array
					}

					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Hasil Perhitungan Vektor S</b></div>";
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<thead><tr><th>Alternatif</th><th>S</th></tr></thead>";
					echo "<tbody>";

					// Pagination setup
					$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
					$limit = 10;
					$offset = ($page - 1) * $limit;
					
					$ss_paginated = array_slice($ss, $offset, $limit, true);
					foreach ($ss_paginated as $i => $value) {
						echo "<tr><td><b>A" . ($i + 1) . "</b></td>";
						echo "<td>" . round($value, 6) . "</td></tr>";
					}
					echo "</tbody></table>";

					$total_data = count($ss);
					$total_pages = ceil($total_data / $limit);

					echo "<div style='text-align: center;'>";
					echo "<nav aria-label='Page navigation'>";
					echo "<ul class='pagination' style='display: inline-block;'>";

					if ($page > 1) {
						echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Sebelumnya</a></li>";
					}
					for ($i = 1; $i <= $total_pages; $i++) {
						$active = ($i == $page) ? "active" : "";
						echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
					}
					if ($page < $total_pages) {
						echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Selanjutnya</a></li>";
					}
					echo "</ul></nav>";
					echo "</div>";

					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Nilai V <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Hasil Perhitungan Nilai V</b></div>";
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<thead><tr><th>Alternatif</th><th>V</th></tr></thead>";
					echo "<tbody>";

					$total_s = array_sum($ss); // Menghitung total nilai S
					$v = []; // Menyimpan hasil perhitungan nilai V
					foreach ($ss as $i => $value) { // Perluangan hasil perhitungan vektor S 
						$v[$i] = round($value / $total_s, 6); // Menghitung nilai V (membagi nilai vektor S setiap alternatif dengan total nilai S)
					}

					$v_paginated = array_slice($v, $offset, $limit, true);
					foreach ($v_paginated as $i => $value) {
						echo "<tr><td><b>" . $alt_name[$i] . "</b></td>";
						echo "<td>" . $value . "</td></tr>";
					}
					echo "</tbody></table>";

					echo "<div style='text-align: center;'>";
					echo "<nav aria-label='Page navigation'>";
					echo "<ul class='pagination' style='display: inline-block;'>";

					if ($page > 1) {
						echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Sebelumnya</a></li>";
					}
					for ($i = 1; $i <= $total_pages; $i++) {
						$active = ($i == $page) ? "active" : "";
						echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
					}
					if ($page < $total_pages) {
						echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Selanjutnya</a></li>";
					}
					echo "</ul></nav>";
					echo "</div></div>";


							// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Hasil Peringkat V <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
							uasort($v,'cmp'); // Mengurutkan nilai V
							echo "<div class='panel' style='border-color: #2FA4E7;'>";
							echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Hasil Peringkat V</b></div>";
							echo "<div class='panel-body'>";
							echo "<table class='table table-striped table-bordered table-hover'>";
							echo "<thead><tr><th>Ranking</th><th>Alternatif</th><th>V</th></tr></thead>";
							echo "<tbody>";				
							
							// Pagination
							$limit = 10; // Jumlah data per halaman
							$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
							$offset = ($page - 1) * $limit;

							// Urutkan data berdasarkan hasil perhitungan Vektor V (dari tinggi ke rendah)
							$sortedV = $v;
							arsort($sortedV);

							// Potong array hasil perhitungan berdasarkan halaman yang aktif
							$paginatedV = array_slice($sortedV, $offset, $limit, true);
							$totalData = count($sortedV);
							$totalPages = ceil($totalData / $limit);

							// Mengurutkan nilai dari tertinggi ke terendah
							$rank = $offset + 1;
							$lastRank = count($sortedV);
							
							foreach ($paginatedV as $altIndex => $value) {
								$rowClass = "";
								if ($rank == 1) {
									$rowClass = "success"; // Warna hijau untuk peringkat 1
								} elseif ($rank == $lastRank) {
									$rowClass = "danger"; // Warna merah untuk peringkat terakhir
								}	

								echo "<tr class='$rowClass'>";
								echo "<td><b>$rank</b></td>";
								echo "<td><b>".$alt_name[$altIndex]."</b></td>";
								echo "<td>".$value."</td>";
								echo "</tr>";
								$rank++;
								}
								echo "</tbody></table>"; 

								// Pagination
								echo "<div style='text-align: center;'>";
								echo "<nav aria-label='Page navigation'>";
								echo "<ul class='pagination' style='display: inline-block;'>";


								// Tombol Sebelumnya
								$prevPage = ($page > 1) ? $page - 1 : 1;
								echo "<li class='".($page == 1 ? "disabled" : "")."'><a href='perhitungan.php?page=$prevPage'>&laquo; Sebelumnya</a></li>";

								for ($i = 1; $i <= $totalPages; $i++) {
									$active = ($i == $page) ? "active" : "";
									echo "<li class='$active'><a href='perhitungan.php?page=$i'>$i</a></li>";
								}

								// Tombol Selanjutnya
								$nextPage = ($page < $totalPages) ? $page + 1 : $totalPages;
								echo "<li class='".($page == $totalPages ? "disabled" : "")."'><a href='perhitungan.php?page=$nextPage'>Selanjutnya &raquo;</a></li>";

								echo "</ul>";
								echo "</nav>";

								echo "</div></div>";
												function jml_kriteria(){
													include 'configdb.php';
													$kriteria = $mysqli->query("select * from kriteria");
													return $kriteria->num_rows;
												}
		
												function jml_alternatif(){
													include 'configdb.php';
													$alternatif = $mysqli->query("select * from alternatif");
													return $alternatif->num_rows;
												}
		
												function get_kepentingan(){
													include 'configdb.php';
													$kepentingan = $mysqli->query("select * from kriteria");
													if(!$kepentingan){
														echo $mysqli->connect_errno." - ".$mysqli->connect_error;
														exit();
													}
													$i=0;
													while ($row = $kepentingan->fetch_assoc()) {
														@$kep[$i] = $row["kepentingan"];
														$i++;
													}
													return $kep;
												}
		
												function get_costbenefit(){
													include 'configdb.php';
													$costbenefit = $mysqli->query("select * from kriteria");
													if(!$costbenefit){
														echo $mysqli->connect_errno." - ".$mysqli->connect_error;
														exit();
													}
													$i=0;
													while ($row = $costbenefit->fetch_assoc()) {
														@$cb[$i] = $row["cost_benefit"];
														$i++;
													}
													return $cb;
												}
		
												function get_alt_name(){
													include 'configdb.php';
													$alternatif = $mysqli->query("select * from alternatif");
													if(!$alternatif){
														echo $mysqli->connect_errno." - ".$mysqli->connect_error;
														exit();
													}
													$i=0;
													while ($row = $alternatif->fetch_assoc()) {
														@$alt[$i] = $row["alternatif"];
														$i++;
													}
													return $alt;
												}
		
												function get_alternatif(){
													include 'configdb.php';
													$alternatif = $mysqli->query("select * from alternatif");
													if(!$alternatif){
														echo $mysqli->connect_errno." - ".$mysqli->connect_error;
														exit();
													}
													$i=0;
													while ($row = $alternatif->fetch_assoc()) {
														@$alt[$i][0] = $row["k1"];
														@$alt[$i][1] = $row["k2"];
														@$alt[$i][2] = $row["k3"];
														@$alt[$i][3] = $row["k4"];
														@$alt[$i][4] = $row["k5"];
														@$alt[$i][5] = $row["k6"];
														@$alt[$i][6] = $row["k7"];
														@$alt[$i][7] = $row["k8"];
														@$alt[$i][8] = $row["k9"];
														@$alt[$i][9] = $row["k10"];
														@$alt[$i][10] = $row["k11"];
														$i++;
													}
													return $alt;
												}
		
												function cmp($a, $b){
													if ($a == $b) {
														return 0;
													}
													return ($a < $b) ? -1 : 1;
												}
		
												function print_ar(array $x){	//just for print array
													echo "<pre>";
													print_r($x);
													echo "</pre></br>";
												}
						?>
					</center>
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
		