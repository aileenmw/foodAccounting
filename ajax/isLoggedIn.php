<?php
require("../session.php");

if ( $_SESSION['login'] == 1) {
  echo 1;
} else {
    echo 0;
}