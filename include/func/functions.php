<?php
	
// Check User Staus
	function checkUserStatus($user) {
		global $con;
		$stmt = $con->prepare("select UserName, RegStatus FROM users WHERE UserName = ? And RegStatus = 0");
		$stmt->execute(array($user));
		$count = $stmt->rowCount();
		if($count > 0) {
			return false;
		} else {
			return true;
		}
	}

// Get All
	function getAll ($selector, $tableName, $condition=Null, $order=Null){
		global $con;
		$stmt = $con->prepare("SELECT $selector FROM $tableName $condition $order");
		$stmt->execute();
		$val = $stmt->fetchAll();
		return $val;
		}

// Get All Categories in DB
	function getAllItems() {
		global $con;
		$stmt = $con->prepare("SELECT * FROM items WHERE Approve != 0");
		$stmt->execute();
		$val = $stmt->fetchAll();
		return $val;
	}


// Get All Categories in DB
	function getCats() {
		global $con;
		$stmt = $con->prepare("SELECT * FROM categories");
		$stmt->execute();
		$cats = $stmt->fetchAll();
		return $cats;
	}

// Get All Approved Items for a specific Category or For All Categories 
	// if you assign category id to the function , it return all approved items for this category 
	// if you call the function without parameters , it return all approved items 
/*
	function getApprovedItems($cid='') {
		global $con;
		$queryplus = '';
		if(!empty($cid)){
			$queryplus = "AND CatID = " . $cid;
		}
		$stmt = $con->prepare("SELECT * FROM Items WHERE Approve != 0 $queryplus ORDER BY ItemID DESC");
		$stmt->execute();
		$cats = $stmt->fetchAll();
		return $cats;
	}
*/
	function getApprovedItems($operator='ItemID', $value='') {
		global $con;

		$condition = $operator;
		$queryplus = "AND " . $condition . " = " . $value;

		$stmt = $con->prepare("SELECT * FROM Items WHERE Approve != 0 $queryplus ORDER BY ItemID DESC");
		$stmt->execute();
		$cats = $stmt->fetchAll();
		return $cats;
	}

	function getPendingItems($operator='ItemID', $value='') {
		global $con;
		$condition = $operator;
		$queryplus = "AND " . $condition . " = " . $value;

		$stmt = $con->prepare("SELECT * FROM Items WHERE Approve = 0 $queryplus ORDER BY ItemID DESC");
		$stmt->execute();
		$cats = $stmt->fetchAll();
		return $cats;
	}

// Function for Printing page Title 

function printTitle() {
	global $pageTitle;
	if (isset($pageTitle)) {
		echo $pageTitle;
	} else {
		echo "Default";
	}
}

	// Function for Redirect

function errorRedirect($errorMsg, $URL = '', $seconds=2){

	if($URL == 'back'){
		if (isset($_SERVER['HTTP_REFERER'])) {
			$URL = $_SERVER['HTTP_REFERER'];
		} else {
			$URL = "javascript:history.go(-1)";
		}
	} elseif($URL == '') {
			echo "<div class='text-center'>";
				echo "<div class='alert alert-danger text-center'>" .$errorMsg. "</div>";
			echo "</div>";
	}else {
	
		echo "<div class='container text-center'>";
			echo "<div class='alert alert-danger text-center'>" .$errorMsg. "</div>";
			echo "<div class='alert alert-info text-center'>You Will Redirect to " . $URL . " after ".$seconds." Seconds</div>";
		echo "</div>";
		header("refresh:$seconds;url=$URL");
		exit();
	}
}


function successRedirect($successMsg, $URL="", $seconds=2){

		if($URL == 'back'){
			if (isset($_SERVER['HTTP_REFERER'])) {
				$URL = $_SERVER['HTTP_REFERER'];
			}else {
				$URL = "javascript:history.go(-1)";
			}

		}elseif($URL == '') {
				echo "<div class='text-center'>";
					echo "<div class='alert alert-success text-center'>" . $successMsg . "</div>";
				echo "</div>";
				
		} else {

				echo "<div class='container'>";
				echo "<div class='alert alert-success text-center'>" . $successMsg . "</div>";
				echo "<div class='alert alert-info text-center'>You Will Redirect to " . $URL . " after ".$seconds." Seconds</div>";
				echo "</div>";
				header("refresh:$seconds;url=$URL");
				exit();
		}
		
}		

// Check if Item (user - Category - product - etc) if already exist in DB or Not for Insert in First Time

function checkexist($item,$table,$value){
	global $con;
	$queryStmt = $con->prepare("SELECT $item FROM $table WHERE $item = ?");
	$queryStmt->execute(array($value));
	$count = $queryStmt->rowCount();
	 if($count > 0) {
	 	return true;
	 } else {
	 	return false;
	 }
}


// Check if Item (user - Category - product - etc) if already exist in DB or Not when update Exsisting Item
function checkexist_update($itemName,$table,$namevalue,$itemID,$idValue){
	global $con;
	$queryStmt = $con->prepare("SELECT $itemName FROM $table WHERE $itemName = ? AND $itemID != ?");
	$queryStmt->execute(array($namevalue, $idValue));
	$count = $queryStmt->rowCount();
	if($count > 0) {
	 	return true;
	} else {
	 	return false;
	}
}

// GET Item Count Function 

function getCount($item,$table){
	global $con;
	$queryStmt = $con->prepare("SELECT COUNT($item) FROM $table");
	$queryStmt->execute();
	return $queryStmt->fetchColumn();
	 
}


// Get Pending Users
function pendingUserCount(){
	global $con;
	$queryStmt = $con->prepare("SELECT COUNT(UserID) FROM users WHERE RegStatus = 0");
	$queryStmt->execute();
	return $queryStmt->fetchColumn();
	 
}


// Get Latest Item from DB (Users - Category - product)

function getLatest($selector, $Table, $order, $Limit = 5){
	global $con;
	$queryStmt = $con->prepare("SELECT $selector FROM $Table ORDER By $order DESC LIMIT $Limit");
	$queryStmt->execute();
	return $queryStmt->fetchAll(); 
}


