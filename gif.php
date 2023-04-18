<?php
	// EDIT "C:\inetpub\wwwroot\gif.php" for dev changes
	//Leave all this stuff as it is
	date_default_timezone_set('America/Los_Angeles');
	include 'GIFEncoder.class.php';
	include 'php52-fix.php';
	$future_date = new DateTime(date('r', strtotime("6 April 2023")));
	$time_now = time();
	$now = new DateTime(date('r', $time_now));
	$frames = array();
	$delays = array();


	// Your image link
	$image_path = __DIR__ . DIRECTORY_SEPARATOR . 'images/background.png';
	$image = imagecreatefrompng($image_path);

	$delay = 100;// milliseconds

	$font = array(
		'size' => 48, // Font size, in pts usually.
		'angle' => 0, // Angle of the text
		'x-offset' => 16, // The larger the number the further the distance from the left hand side, 0 to align to the left.
		'y-offset' => 65, // The vertical alignment, trial and error between 20 and 60.
		'file' => __DIR__ . DIRECTORY_SEPARATOR . 'CentraNo2-Book-Monospace-Digits.woff', // Font path
		'color' => imagecolorallocate($image, 0, 0, 0), // RGB Color of the text
	);
	for($i = 0; $i <= 60; $i++){

		$interval = date_diff($future_date, $now);

		if($future_date < $now){
			// Open the first source image and add the text.
			$image = imagecreatefrompng($image_path);
			;
			$text = "00    00    00    00";
			imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text);
			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 1;
			ob_end_clean();
			break;
		} else {
			// Open the first source image and add the text.
			$image = imagecreatefrompng($image_path);
			;
			$text = $interval->format('%D   %H   %I   %S');
			imagettftext ($image , $font['size'] , $font['angle'] , $font['x-offset'] , $font['y-offset'] , $font['color'] , $font['file'], $text);
			ob_start();
			imagegif($image);
			$frames[]=ob_get_contents();
			$delays[]=$delay;
			$loops = 0;
			ob_end_clean();
		}

		$now->modify('+1 second');
	}

	//expire this image instantly
	header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
	$gif = new AnimatedGif($frames,$delays,$loops);
	$gif->display();
