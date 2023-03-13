<?php

/**
 *  @$oldDebt
 *  @$payed
 *  @$balance
 *  @$adults
 *  @$teen @$eaten @$newDebt;
 * 
 */

class Bill {
  // Properties
  public $name;
  public $nmb;
  public $oldDebt;
  public $payed;
  public $balance;
  public $adults;
  public $teen;
  public $children;
  public $eaten;
  public $newDebt;
  

  function set_nmb($nmb) {
    $this->name = $nmb;
  }
  function get_nmb() {
    return $this->nmb;
  } 
  
  function set_name($name) {
    $this->name = $name;
  }
  function get_name() {
    return $this->name;
  }

  function set_oldDebt($oldDebt) {
    $this->oldDebt = $oldDebt;
  }
  function get_oldDebt() {
    return $this->oldDebt;
  }

  function set_payed($payed) {
    $this->payed = $payed;
  }
  function get_payed() {
    return $this->payed;
  }

  function set_balance($balance) {
    $this->balance = $balance;
  }
  function get_balance() {
    return $this->balance;
  }

  function set_adults($adults) {
    $this->adults = $adults;
  }
  function get_adults() {
    return $this->adults;
  }

  function set_teen($teen) {
    $this->teen = $teen;
  }
  function get_teen() {
    return $this->teen;
  }

  function set_children($children) {
    $this->children = $children;
  }
  function get_children() {
    return $this->children;
  }

  function set_eaten($eaten) {
    $this->eaten = $eaten;
  }
  function get_eaten() {
    return $this->eaten;
  }

  function set_newDebt($newDebt) {
    $this->newDebt = $newDebt;
  }
  function get_newDebt() {
    return $this->newDebt;
  }

}