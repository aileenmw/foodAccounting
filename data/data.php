<?php

    $xmlPosts = [ "Navn" => "name", "Efternavn" => "lname", "Udestående" => "debt", "Indbetalt" => "payed", "Saldo" => "balance", 
    "Voksne" => "adult", "Pubber" => "teen", "Børn" => "child", "Spist" => "eaten", "Udlæg" => "expenses", "Regning" => "bill", "Depositum" => "deposit" ]; 

 function checkVal ($val) {
    if($val == null || $val == 'NaN' || $val == "") {
        $val = 0;
    }
    return $val;
 }

    // $residents = [ 
    //     1 => [
    //         "name" => "Rudy",
    //         "debt" => 350,
    //     ], 
    //     3 => [
    //         "name" => "Benny",
    //         "debt" => 150,
    //     ],  
    //     5 => [
    //         "name" => "Morten og Catrine",
    //         "debt" => 345,
    //     ], 
    //     7 => [
    //         "name" => "Charlotte",
    //         "debt" => 765,
    //     ], 
    //     9 => [
    //         "name" => "Maria",
    //         "debt" => 465,
    //     ], 
    //     11 => [
    //         "name" => "Aileen",
    //         "debt" => 155,
    //     ], 
    //     13 => [
    //         "name" => "Jens og Anita",
    //         "debt" => 865,
    //     ], 
    //     15 => [
    //         "name" => "Sofie og Jens",
    //         "debt" => 965,
    //     ], 
    //     17 => [
    //         "name" => "Torsten",
    //         "debt" => 45,
    //     ], 
    //     19 => [
    //         "name" => "Tina",
    //         "debt" => 175,
    //     ], 
    //     21 => [
    //         "name" => "Pernille",
    //         "debt" => 765,
    //     ], 
    //     23 => [
    //         "name" => "Helle",
    //         "debt" => 155,
    //     ], 
    //     25 => [
    //         "name" => "Anna-Mette",
    //         "debt" => 230,
    //     ], 
    //     27 => [
    //         "name" => "Katia og Frederik",
    //         "debt" => 350,
    //     ], 
    //     29 => [
    //         "name" => "Henriette",
    //         "debt" => 144,
    //     ], 
    //     31 => [
    //         "name" => "Maria",
    //         "debt" => 345,
    //     ], 
    //     33 => [
    //         "name" => "Anne-Grete",
    //         "debt" => 234,
    //     ], 
    //     35 => [
    //         "name" => "Lars",
    //         "debt" => 565,
    //     ], 
    //     37 => [
    //         "name" => "Rie",
    //         "debt" => 0,
    //     ], 
    //     39 => [
    //         "name" => "Gertrud",
    //         "debt" => 245,
    //     ], 
    // ];

?>