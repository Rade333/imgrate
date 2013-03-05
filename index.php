<?php
	// INCLUDES
	include 'inc.php';

	// FUNCTIONS

	// Select images from database and create objects
	function dbGetImages($query)
	{
		$result = mysql_query($query) or die(mysql_error());
		$i = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$img[$i] = new Image($row['id']);
			$i++;
		}
		return $img;
	}

	// Selects randomly two records from database
	function selectImages()
	{
		global $config;
		$img = dbGetImages("SELECT `id` FROM `$config[1]` ORDER BY RAND() LIMIT 2");
		return $img;
	}

	// Save users vote
	function voteImage($win, $lose)
	{
		global $config;

		// Set scores
		setScores("wins", $win);
		setScores("losses", $lose);

		// Set new ratings
		setRatings($win, $lose);

                // Record vote
                mysql_query("INSERT INTO `$config[2]` (`win`, `lose`) VALUES ($win, $lose)") or die(mysql_error());
	}

	// Save the scores of a vote in database
	function setScores($game, $id)
	{
		global $config;

		// Set win or loss
		$query = mysql_query("SELECT `$game` FROM `$config[1]` WHERE `id`=$id LIMIT 1") or die(mysql_error());
		$result = mysql_fetch_assoc($query);
		$score = $result[$game] + 1;
		mysql_query("UPDATE `$config[1]` SET `$game` = $score WHERE `id` = $id") or die(mysql_error());
	}

	// Calculate and save new ratings
	function setRatings($win, $lose)
	{
		global $config;
	
		// Set constant
		$K = 32;

		// Winner is always A, loser is B
		$Wa = 1;
		$Wb = 0;

		// Get winners current rating
		$query = mysql_query("SELECT `id`, `rating` FROM `$config[1]` WHERE `id` = $win LIMIT 1") or die(mysql_error());
		$result = mysql_fetch_assoc($query);
		$Ra = $result['rating'];

		// Get losers current rating 
                $query = mysql_query("SELECT `id`, `rating` FROM `$config[1]` WHERE `id` = $lose LIMIT 1") or die(mysql_error());
                $result	= mysql_fetch_assoc($query);
                $Rb = $result['rating'];

		// Probabilities of winning
		$Ea = 1 / (1 + pow(10, (($Rb - $Ra) / 400)));
		$Eb = 1 / (1 + pow(10, (($Ra - $Rb) / 400)));

		// Print values (for testing)
		// print 'Ra: ' . round($Ra, 2) . '<br />';
		// print 'Rb: ' . round($Rb, 2) . '<br />';
		// print 'Ea: ' . round($Ea, 2) . '<br />';
		// print 'Eb: ' . round($Eb, 2) . '<br />'; 		

		// Calculate new ratings
		$Ra = $Ra + $K * ($Wa - $Ea);
		$Rb = $Rb + $K * ($Wb - $Eb);

		// Save new ratings in database
		mysql_query("UPDATE `$config[1]` SET `rating` = $Ra WHERE `id` = $win LIMIT 1") or die(mysql_error());
		mysql_query("UPDATE `$config[1]` SET `rating` = $Rb WHERE `id` = $lose LIMIT 1") or die(mysql_error());
		
	}

	function confirmDelete($id)
	{
		$image = new Image($id);
		
		if (!$image->id)
		{
			$text = 'Error: File could not be found.';
			$type = 1;
		}
		else
		{
			$text = 'Are you sure you want to remove image <em>' . $image->src . '</em>?<br />This action cannot be undone.';
			$text .= '<br /><a href="?do=delete&id=' . $image->id . '">Delete</a> | <a href="?">Cancel</a>';
			$type = 2;
		}
		return array ($text, $type);
	}

	function deleteImage($id)
	{
		global $config;

                // Create instance
                $image = new Image($id);

		if (!$image->id)
		{
			$text = 'Error: Image could not be deleted.';
			$type = 1;
		}
		else
		{
                	// Delete from database
			mysql_query("DELETE FROM `$config[1]` WHERE `id` = $id") or die(mysql_error());

	                // Delete from server
			unlink('images/' . $image->src);

			$text = 'Image <em>' . $image->src . '</em> was successfully deleted.';
			$type = 0;
		}
		return array ($text, $type);
	}

	function getValue($field)
	{
		return $_GET[$field];
	}

	// ACTIONS

	dbConnect($config[0]);

	$do = $_GET['do'];

	// Do: Log out
	if ($do == 'logout')
	{
		// do logout
		if (checkLogin())
		{
			setcookie($cookie_name, $cookie_value_logout, $cookie_expire, $cookie_path, $cookie_domain, 0);
			header('Location: ' .$request_uri);
		}
		else
		{
			$message = new Message('You are now logged out.', 0);
		}
	}

	// Do: Vote
	if ($do == 'vote')
	{
		// Get images
		$win = $_GET['win'];
		$lose = $_GET['lose'];
		
		// Send vote 
		voteImage($win, $lose);

		// Set message
		$message = new Message("Vote recorded successfully.", 0);
	}

	// Do: confirmDelete
	if ($do == 'confirmDelete')
	{
		// Call function and set message
		$message = new Message(confirmDelete(getValue('id')));
	}

	// Do: Delete
	if ($do == 'delete')
	{
		// Call function and set message
		$message = new Message(deleteImage(getValue('id')));
	}
?>

<?php include 'inc.head.php'; ?>
		<title>ImgRate</title>
	</head>
	<body>
		<?php include 'inc.message.php'; ?>
		<div class="container front">
		<?php include 'inc.nav.php'; ?>
			<?php
				$img = selectImages();
			?>
			<h1 class="title">Rate</h1>
			<div class="description">Below you see two randomly selected images. Choose which you like better by clicking the image. On the Stats-page you can find the ratings of the images.</div>
			<div class="images">
				<div class="images region">
				<div class="img first">
					<a href="?do=vote&win=<?php print $img[0]->id; ?>&lose=<?php print $img[1]->id; ?>" title="Vote this image">
						<img src="images/<?php print $img[0]->src; ?>" />
					</a>
				</div>
				<div class="img last">
					<a href="?do=vote&win=<?php print $img[1]->id; ?>&lose=<?php print $img[0]->id; ?>" title="Vote this image">
						<img src="images/<?php print $img[1]->src; ?>" />
					</a>
				</div>
				</div>
			</div>
		</div>

		<?php include 'inc.footer.php'; ?>
	</body>
</html>
