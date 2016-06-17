<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap/apps/shared/db_connect.php';
require_once "../includes/functions.php";

// Start session or regenerate session id
sec_session_start();

// if the delete button was clicked
	// mark the file inactive
	// if there are more than 10 inactive files
		// delete oldest inactive file from database and server

?>


