<?php
include 'configdb.php';

function jml_kriteria(){
    global $mysqli;
    return $mysqli->query("SELECT * FROM kriteria")->num_rows;
}

function jml_alternatif(){
    global $mysqli;
    return $mysqli->query("SELECT * FROM alternatif")->num_rows;
}

function get_kepentingan(){
    global $mysqli;
    $data = [];
    $q = $mysqli->query("SELECT kepentingan FROM kriteria");
    while($r = $q->fetch_assoc()){
        $data[] = $r['kepentingan'];
    }
    return $data;
}

function get_costbenefit(){
    global $mysqli;
    $data = [];
    $q = $mysqli->query("SELECT cost_benefit FROM kriteria");
    while($r = $q->fetch_assoc()){
        $data[] = $r['cost_benefit'];
    }
    return $data;
}

function get_alt_name(){
    global $mysqli;
    $data = [];
    $q = $mysqli->query("SELECT alternatif FROM alternatif");
    while($r = $q->fetch_assoc()){
        $data[] = $r['alternatif'];
    }
    return $data;
}

function get_alternatif(){
    global $mysqli;
    $alt = [];
    $q = $mysqli->query("SELECT * FROM alternatif");
    $i = 0;

    while ($r = $q->fetch_assoc()) {
        $alt[$i] = [
            $r["k1"],
            $r["k2"],
            $r["k3"],
            $r["k4"],
            $r["k5"],
            $r["k6"],
            $r["k7"],
            $r["k8"],
            $r["k9"],
            $r["k10"],
            $r["k11"] // ⬅️ STOP DI SINI
        ];
        $i++;
    }
    return $alt;
}


function cmp($a, $b){
    return $a <=> $b;
}
