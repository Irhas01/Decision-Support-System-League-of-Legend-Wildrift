<?php
	session_start();
	include('configdb.php');

// Tentukan jumlah data per halaman
$jumlahDataPerHalaman = 10;

// Cek apakah totalDataV sudah ada di session
if (!isset($_SESSION['totalDataV'])) {
    $result = $mysqli->query("SELECT COUNT(*) AS total FROM alternatif");
    $row = $result->fetch_assoc();
    $_SESSION['totalDataV'] = $row['total'];
}

// Hitung total halaman berdasarkan session
$totalDataV = $_SESSION['totalDataV'];
$totalPagesV = ceil($totalDataV / $jumlahDataPerHalaman);
	
$halamanAktif = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$startData = ($halamanAktif - 1) * $jumlahDataPerHalaman;

// Ambil data dari database berdasarkan halaman
$query = "SELECT * FROM alternatif LIMIT $startData, $jumlahDataPerHalaman";
$dataAlternatif = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title><?php echo $_SESSION['judul']." - ".$_SESSION['by'];?></title>

    <!-- Bootstrap core CSS -->
	<link href="ui/css/cerulean.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="ui/css/jumbotron.css" rel="stylesheet">

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
			  <li class="active"><a href="#">Analisa</a></li>
              <li><a href="perhitungan.php">Perhitungan</a></li>
			</ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
		<div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="panel panel-primary">
		  <!-- Default panel contents -->
		  <div class="panel-heading">Analisa</div>
		  <div class="panel-body">
			<div>
				<canvas id="canvas"></canvas>
			</div>
			<div style="text-align: center; margin-top: 10px;">
			<button id="prevBtn" class="btn btn-primary">Sebelumnya</button>
			<button id="nextBtn" class="btn btn-primary">Selanjutnya</button>
			</div>
			
			<br />
			<center>
				<?php

					$alt = get_alternatif();
					$alt_name = get_alt_name();
					end($alt_name);
					$arl2 = key($alt_name)+1; //new
					$kep = get_kepentingan();
					$cb = get_costbenefit();
					$k = jml_kriteria();
					$a = jml_alternatif();
					$tkep = 0;
					$tbkep = 0;

						for($i=0;$i<$k;$i++){ // loop untuk menghitung total kepentingan
							$tkep = $tkep + $kep[$i]; // total kepentingan
						}
						for($i=0;$i<$k;$i++){ // loop untuk menghitung bobot kepentingan
							$bkep[$i] = ($kep[$i]/$tkep); // kepentingan dibagi total kepentingan
							$tbkep = $tbkep + $bkep[$i]; // total bobot kepentingan
						}
						for($i=0;$i<$k;$i++){ // loop untuk menghitung nilai alternatif
							if($cb[$i]=="cost"){ // informasi jika cost
								$pangkat[$i] = (-1) * $bkep[$i]; // pangkat negatif
							}
							else{
								$pangkat[$i] = $bkep[$i]; // informasi jika benefit pangkat positif
							}
						}
					for($i=0;$i<$a;$i++){ // loop untuk menghitung nilai alternatif
						for($j=0;$j<$k;$j++){ // loop kedua untuk menghitung nilai alternatif 
							$s[$i][$j] = pow(($alt[$i][$j]),$pangkat[$j]); // nilai alternatif pangkat bobot kepentingan
						}
						$ss[$i] = $s[$i][0]*$s[$i][1]*$s[$i][2]*$s[$i][3]*$s[$i][4]*$s[$i][5]*$s[$i][6]*$s[$i][7]*$s[$i][8]*$s[$i][9]*$s[$i][10]; // hasil akhir dari nilai alternatif
					}
					
					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Nilai V <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Hasil Nilai V</b></div>";					
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered table-hover'>";
					echo "<thead><tr><th>Alternatif</th><th>V</th></tr></thead>";
					
					$total = 0; // Menghitung total nilai S
					for($i=0;$i<$a;$i++){ // loop untuk menghitung total nilai
						$total = $total + $ss[$i]; // total nilai
					}
					for($i=0;$i<$a;$i++){ // loop untuk menghitung nilai V
						$v[$i] = round($ss[$i]/$total,6); // nilai V
					}

					// Pagination
					$limit_v = 10; // Jumlah data per halaman
					$page_v = isset($_GET['page_v']) ? (int)$_GET['page_v'] : 1;
					$offset_v = ($page_v - 1) * $limit_v;
					
					// Urutkan data berdasarkan hasil perhitungan
					$totalDataV = count($v);
					$totalPagesV = ceil($totalDataV / $limit_v);

					// Ambil bagian data sesuai dengan halaman yang aktif
					$paginatedV = array_slice($v, $offset_v, $limit_v, true);

					foreach ($paginatedV as $altIndex => $value) {
						echo "<tr><td><b>" . $alt_name[$altIndex] . "</b></td>";
						echo "<td>" . $value . "</td></tr>";
					}
					echo "</table>";

					// Pagination untuk Hasil Nilai V
					echo "<nav aria-label='Page navigation'>";
					echo "<ul class='pagination'>";
					
					echo "</table><hr>";
					// Tombol Sebelumnya
					$prevPageV = ($page_v > 1) ? $page_v - 1 : 1;
					echo "<li class='" . ($page_v == 1 ? "disabled" : "") . "'><a href='analisa.php?page_v=$prevPageV'>&laquo; Sebelumnya</a></li>";

					for ($i = 1; $i <= $totalPagesV; $i++) {
						$active = ($i == $page_v) ? "active" : "";
						echo "<li class='$active'><a href='analisa.php?page_v=$i'>$i</a></li>";
					}

					// Tombol Selanjutnya
					$nextPageV = ($page_v < $totalPagesV) ? $page_v + 1 : $totalPagesV;
					echo "<li class='" . ($page_v == $totalPagesV ? "disabled" : "") . "'><a href='analisa.php?page_v=$nextPageV'>Selanjutnya &raquo;</a></li>";

					echo "</ul>";
					echo "</nav>";
					echo "</div></div>";
					
					// >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> Hasil Peringkat V <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< //
					uasort($v,'cmp'); // Mengurutkan array $v berdasarkan nilai
					echo "<div class='panel' style='border-color: #2FA4E7;'>";
					echo "<div class='panel-heading' style='background-color: #2FA4E7; color: white;'><b>Hasil Peringkat Alternatif</b></div>";					
					echo "<div class='panel-body'>";
					echo "<table class='table table-striped table-bordered'>";
					echo "<thead><tr><th>Peringkat</th><th>Alternatif</th><th>Nilai</th></tr></thead>";
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
						echo "<nav aria-label='Page navigation'>";
						echo "<ul class='pagination'>";

						// Tombol Sebelumnya
						$prevPage = ($page > 1) ? $page - 1 : 1;
						echo "<li class='".($page == 1 ? "disabled" : "")."'><a href='analisa.php?page=$prevPage'>&laquo; Sebelumnya</a></li>";

						for ($i = 1; $i <= $totalPages; $i++) {
							$active = ($i == $page) ? "active" : "";
							echo "<li class='$active'><a href='analisa.php?page=$i'>$i</a></li>";
						}

						// Tombol Selanjutnya
						$nextPage = ($page < $totalPages) ? $page + 1 : $totalPages;
						echo "<li class='".($page == $totalPages ? "disabled" : "")."'><a href='analisa.php?page=$nextPage'>Selanjutnya &raquo;</a></li>";

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
										@$alt[$i][11] = $row["k12"];
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
	<script src="ui/js/Chart.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="ui/js/ie10-viewport-bug-workaround.js"></script>
	<!-- chart -->
	<script>
	var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
    labels : [
        <?php
            $counter = 0;
            foreach ($sortedV as $altIndex => $value) {
                if ($counter < 10) { // Hanya 10 data teratas
                    echo '"'.$alt_name[$altIndex].'",';
                }
                $counter++;
            }
        ?>
    ],
    datasets : [
        {
            fillColor : "rgba(0,0,255,0.75)",
            strokeColor : "rgba(220,220,220,0.8)",
            highlightFill: "rgba(0,128,255,0.75)",
            highlightStroke: "rgba(220,220,220,1)",
            data : [
                <?php
                    $counter = 0;
                    foreach ($sortedV as $altIndex => $value) {
                        if ($counter < 10) { // Hanya 10 data teratas
                            echo $value.',';
                        }
                        $counter++;
                    }
                ?>
            ]
        },
    ]
};
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, {
			responsive : true
		});
	}

	</script>
	<script>
    var sortedV = <?php echo json_encode($sortedV); ?>; 
    var altNames = <?php echo json_encode($alt_name); ?>;
    var perPage = 10; 
    var currentPage = 0;
    var totalPages = Math.ceil(Object.keys(sortedV).length / perPage);

    function updateChart() {
        var start = currentPage * perPage;
        var end = start + perPage;
        var keys = Object.keys(sortedV).slice(start, end);
        
        var labels = keys.map(function(index) {
            return altNames[index];
        });

        var data = keys.map(function(index) {
            return sortedV[index];
        });

        var barChartData = {
            labels: labels,
            datasets: [{
                fillColor: "rgba(0,0,255,0.75)",
                strokeColor: "rgba(220,220,220,0.8)",
                highlightFill: "rgba(0,128,255,0.75)",
                highlightStroke: "rgba(220,220,220,1)",
                data: data
            }]
        };

        var ctx = document.getElementById("canvas").getContext("2d");
        if (window.myBar) {
            window.myBar.destroy();
        }
        window.myBar = new Chart(ctx).Bar(barChartData, { responsive: true });

        updatePagination();
    }

    function updatePagination() {
        var paginationContainer = document.getElementById("pagination");
        paginationContainer.innerHTML = "";

        for (let i = 0; i < totalPages; i++) {
            let pageButton = document.createElement("button");
            pageButton.innerText = i + 1;
            pageButton.classList.add("btn", "btn-secondary", "mx-1");
            if (i === currentPage) {
                pageButton.classList.add("btn-info"); // Tombol aktif akan berwarna biru
            }
            pageButton.addEventListener("click", function() {
                currentPage = i;
                updateChart();
            });
            paginationContainer.appendChild(pageButton);
        }

        document.getElementById("prevBtn").disabled = (currentPage === 0);
        document.getElementById("nextBtn").disabled = (currentPage === totalPages - 1);
    }

    document.getElementById("prevBtn").addEventListener("click", function() {
        if (currentPage > 0) {
            currentPage--;
            updateChart();
        }
    });

    document.getElementById("nextBtn").addEventListener("click", function() {
        if (currentPage < totalPages - 1) {
            currentPage++;
            updateChart();
        }
    });

    window.onload = function() {
        updateChart();
    };
</script>
</body></html>