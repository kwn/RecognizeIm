<?php

//image limist
require_once('imageLimits.php');

/**
 * api call wrapper
 */
class RecognizeImAPI {
	//! soap client instance
	private static $api;
	private static $config;

	//! connect
	public static function init(){
		self::$config = require('config.php');
		self::$api = new SoapClient(NULL, array('location' => self::$config['URL'], 'uri' => self::$config['URL'], 'trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
		$r = self::$api->auth(self::$config['CLIENT_ID'], self::$config['CLAPI_KEY'], NULL);
		if (is_object($r))
			$r = (array)$r;
		if ($r['status']) {
			throw new Exception("Cannot authenticate");
		}
	}

	/**
	 * wrap api call
	 * @param $name fn name
	 * @param $arguments fn args
	 */
	public static function __callStatic($name, $arguments) {
		$r = call_user_func_array(array(self::$api, $name), $arguments);
		if (is_object($r))
			$r = (array)$r;
		if (!$r['status']) {
			return array_key_exists('data', $r)?$r['data']:NULL;
		}		
		throw new Exception($r['message'], $r['status']);
	}

	/**
	 * Checks image limits, depending on the recognition mode
	 * @param $imagePath the path to the file
	 * @param $mode the recognition mode
	 */
	public static function checkImageLimits($imagePath, $mode = 'single') {
		//check the correctness of the selected mode
		if (!in_array($mode, array('single', 'multi')))
			throw new Exception('Wrong \'mode\' value. Should be "single" or "multi"');

		//fetch image data
		$size = filesize($imagePath) / 1000.0;		//KB
		$dimensions = getimagesize($imagePath);
		$width = $dimensions[0];
		$height = $dimensions[1];
		$surface = ($width * $height) / 1000000.0;	//Mpix
		
		//check image data
		if ($mode == 'single') {
			if ($size > SINGLEIR_MAX_FILE_SIZE ||
				$height < SINGLEIR_MIN_DIMENSION ||
				$width < SINGLEIR_MIN_DIMENSION ||
				$surface < SINGLEIR_MIN_IMAGE_SURFACE ||
				$surface > SINGLEIR_MAX_IMAGE_SURFACE ) {
				return FALSE;
			}

		} else {
			if ($size > MULTIIR_MAX_FILE_SIZE ||
				$height < MULTIIR_MIN_DIMENSION ||
				$width < MULTIIR_MIN_DIMENSION ||
				$surface < MULTIIR_MIN_IMAGE_SURFACE ||
				$surface > MULTIIR_MAX_IMAGE_SURFACE ) {
				return FALSE;
			}
		}

		//test passed, return true
		return TRUE;
	}

	/**
	 * Recognize object using image in single mode
	 * @param $image query
	 * @param $mode Recognition mode. Should be 'single' or 'multi'. Default is 'single'.
	 * @param $getAll if TRUE returns all recognized objects in 'single' mode, otherwize only the best one; in 'multi' it enables searching for multiple instances of each object
	 * @returns associative array containg recognition result
	 */
	public static function recognize($image, $mode = 'single', $getAll = FALSE) {
		$hash = md5(self::$config['API_KEY'].$image);
		$url = self::$config['URL'].'v2/recognize/' . $mode . '/';

		if ($getAll)
			$url .= 'all/';

		$url .= self::$config['CLIENT_ID'];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('x-itraff-hash: '.$hash, 'Content-type: image/jpeg'));
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $image);
		$obj = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$res = array();
		if($status != '200') {
			//throw new Exception('Cannot upload photo');
			$res = array('status' => -1, 'message' => 'Cannot upload photo');
		} else {
			$res = (array)json_decode($obj);
		}
		return new RecognizeImAPIResult($res);
	}
};

class RecognizeImAPIResult {
	const STATUS_OK		= 0;
	const STATUS_NO_MATCH	= 1;

	protected $status 	= self::STATUS_OK;
	protected $message 	= '';
	protected $objects	= array();
	
	public function __construct($jsonData) {
		$this->status = $jsonData['status'];
		if ($this->status != self::STATUS_OK) {
			$this->message = $jsonData['message'];
			return;
		}
		foreach ($jsonData['objects'] as $obj) {
			$this->objects[] = new RecognizeImAPIResultObject((array) $obj);
		}
		if (empty($this->objects)) {
			$this->status = self::STATUS_NO_MATCH;
			$this->message = 'No match found';
		}
	}	

	public function __toString() {
		if (!$this->isOK()) {
			return $this->message;
		}
		return 'Status OK {' . implode("\n", $this->objects) . '}';
	}

	public function isOK() {
		return $this->status == self::STATUS_OK;
	}

	public function getMessage() {
		return $this->message;
	}

	public function getObjects() {
		return $this->objects;
	}

	public function drawFrames($file) {
		$im = imagecreatefromstring($file);
		if (!$im) return false;
		$color = imagecolorallocate($im, 255, 255, 255);
		foreach ($this->objects as $object) {
			$location = $object->getLocation();
			$size = count($location);
			for ($i = 0; $i < $size; ++$i) {
				$p1 = $location[$i];
				$p2 = $location[($i+1)%$size];
				imageline($im, $p1['x'], $p1['y'], $p2['x'], $p2['y'], $color);
			}
		}
		ob_start();
		imagejpeg($im);
		$img = ob_get_clean();
		imagedestroy($im);
		return $img;
	}
}

class RecognizeImAPIResultObject {
	protected $id 		= NULL;
	protected $name 	= '';
	protected $location	= array();
	
	public function __construct($jsonData) {
		$this->id 	= $jsonData['id'];
		$this->name 	= $jsonData['name'];
		foreach ($jsonData['location'] as $point) {
			$this->location[] = (array) $point;
		}
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getLocation() {
		return $this->location;
	}

	public function __toString() {
		return $this->name;
	}
}


RecognizeImAPI::init();
