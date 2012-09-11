<?php

// Choose your output format (JSON, XML, HTML)
// ONLY JSON FOR THE MOMENT !
define('API_FORMAT', 'JSON');

// Put your API key here
define('API_KEY', 'YOUR_API_KEY');

/* Don't touch anything now ! */

define('API_URL', "datagamer.fr");

class ApiDatagamer
{
	private static $API_KEY		=	API_KEY;	
	private static $API_URL 	= 	API_URL;	
	private static $API_FORMAT 	= 	API_FORMAT;	
	
	private static $errorException;
	private $_presets 		= 	array();
	private $_lastError		=	null;
	
	/****************************************************************************************
	 *****************************				Utils			*****************************
	****************************************************************************************/
	
    protected static function error($message = null, $code = 0)
    {
    	if ($message !== null)
        {
        	$error = new ErrorException($message, $code);
            throw $error;
			self::$_lastError = $error;            
            return self::$_lastError;
        }
    }

	protected function set($preset, $value=null)
	{
    	if (is_array($preset))
        	foreach($preset as $name => $value)
            	$this->_presets[(string) $name] = $value;
		
        elseif (is_string($preset))
        	$this->_presets[$preset] = $value;

        return $this;
	}
        
	protected function getPresets($preset = null)
	{
    	if ($preset === null)
        	return $this->_presets;
		else
        	return @$this->_presets[$preset];
	}

	protected function clearPresets($presets = array(), $inverse = false)
    {
    	if (empty($presets))
        	$this->_presets = array();
		else
		{
        	if ($inverse)
            	foreach($this->_presets as $psn => $ps)
            	{
                	if (!in_array($psn, $presets))
                		unset($this->_presets[$psn]);
				}
			else
            	foreach($presets as $ps)
                	unset($this->_presets[$ps]);
		}
		return $this;
	}
	
    protected function createURL($type)
    {
    	$this->set(array('controller' => $type, 'format' => self::$API_FORMAT, 'apiKey' => md5(self::$API_KEY)));
		$options_str = array();
    
		foreach ($this->getPresets() as $cle => $valeur)
		{
        	if (is_string($cle))
            {
            	if (is_array($valeur))
                	$options_str[] = $cle."=".implode(',', $valeur);
				else
                	$options_str[] = $cle."=".urlencode((string) $valeur);
			}
            else
            	$options_str[] = (string) $valeur;
		}
		return "http://".self::$API_URL."/api-controller.php?".implode('&',(array)$options_str);
	}
        
	protected function sendURL ($url)
	{
    	if (function_exists("curl_init"))
		{
			$curl = curl_init();
			curl_setopt ($curl, CURLOPT_URL, $url);
			curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
			$data = curl_exec($curl);
			curl_close($curl);
		}            
		else
        {
        	if (!function_exists("file_get_contents"))
            {
				$this->error("The extension php_curl must be installed with PHP or function file_get_contents must be enabled.", 1);
				return false;
			}
            else
            	$data = @file_get_contents($url);
		}
		
		if (empty($data))
        {
        	$this->error("An error occurred while retrieving the data.", 2);
            return false;
		}
		
		$data = @json_decode($data, 1);

		if (empty($data))
		{
			$this->error("An error occurred when converting data.", 3);
            return false;
		}
        else return $data;
	}	
	
	/**
	 * Generic method
	 * @param String $model
	 */
	private function find($model)
	{
		$url	= 	$this->createURL($model);
		$data	=	$this->sendURL($url);
		
		if (empty($data))
			return false;
		else
		{
			if (empty($data['error']))
			{
				return $data[$model];
			}
			else
			{
				$this->error($data['error'], 5);
				return false;
			}
		}
	}
	
	/****************************************************************************************
	 *****************************				Methods			***************************** 
	 ****************************************************************************************/
        
	/**
    * Find a game by id
    * @param int $id Game id
    */
	public function findGameById($id)
	{
		$this->set(array('id' => (int) $id));
		return $this->find("game");
	}
	
	/**
	 * Find a editor by id
	 * @param int $id Editor id
	 */
	public function findEditorById($id)
	{
		$this->set(array('id' => (int) $id));
		return $this->find("editor");
	}	
	
	/**
	 * Find a developer by id
	 * @param int $id Developer id
	 */
	public function findDeveloperById($id)
	{
		$this->set(array('id' => (int) $id));
		return $this->find("developer");
	}	
	
	/**
	 * Find a platform by id
	 * @param int $id Platform id
	 */
	public function findPlatformById($id)
	{
		$this->set(array('id' => (int) $id));
		return $this->find("platform");
	}
	
	/**
	 * Find a genre by id
	 * @param int $id Genre id
	 */
	public function findGenreById($id)
	{
		$this->set(array('id' => (int) $id));
		return $this->find("genre");
	}
		
	/**
	 * Find all games in database
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "id", "name" or "releaseDate"
	 */
	public function findAllGames($order = null, $by = null)
	{
		$this->set(array('order' => $order, 'by' => $by));		
		return $this->find("games");
	}
	
	/**
	 * Find random games in database
	 * @param int $number Number of games to return
	 */
	public function findRandomGames($number = null)
	{
		$this->set(array('number' => $number));
		return $this->find("gamesRandom");
	}
	
	/**
	 * Find all editors in database
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "name" or "id"
	 */
	public function findAllEditors($order = null, $by = null)
	{
		$this->set(array('order' => $order, 'by' => $by));		
		return $this->find("editors");
	}
	
	/**
	 * Find all developers in database
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "name" or "id"
	 */
	public function findAllDevelopers($order = null, $by = null)
	{
		$this->set(array('order' => $order, 'by' => $by));		
		return $this->find("developers");
	}
	
	/**
	 * Find all platforms in database
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "name" or "id"
	 */
	public function findAllPlatforms($order = null, $by = null)
	{
		$this->set(array('order' => $order, 'by' => $by));		
		return $this->find("platforms");
	}
	
	/**
	 * Find all genres in database
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "name" or "id"
	 */
	public function findAllGenres($order = null, $by = null)
	{
		$this->set(array('order' => $order, 'by' => $by));		
		return $this->find("genres");
	}
	
	/**
	 * Find a game by criteria
	 * @param Array $criteria "name" => [String] or "releaseDate" => [timestamp]
	 * @param String $order [optional] "ASC" or "DESC"
	 * @param String $by [optional] "name" or "id"
	 * @param int [optional] $startLimit
	 * @param int [optional] $limit
	 */
	public function findGameByCriteria($criteria, $order = null, $by = null, $startLimit = null, $limit = null)
	{
		$criteria['order'] 		= 	$order;
		$criteria['by'] 		=	$by;
		$criteria['startLimit'] = 	$startLimit;
		$criteria['limit'] 		= 	$limit;		
		$this->set($criteria);
		return $this->find("gameCriteria");
	}

	/**
	 * Add a value to grade attribute for a game
	 * @param int $id
	 * @param float $value [0, 10]
	 */
	public function addGrade($id, $value)
	{
		$this->set(array('id' =>(int) $id, 'value' =>(float) $value));
		return $this->find("addGrade");
	}
	
	/**
	 * Add a value to appreciation attribute for a game
	 * @param int $id
	 * @param int $value [1|-1]
	 */
	public function addAppreciation($id, $value)
	{
		$this->set(array('id' =>(int) $id, 'value' =>$value));
		return $this->find("addAppreciation");
	}

	/**
	 * Add a game
	 * @param String $cover URL format
	 * @param int $releaseDate Timestamp
	 * @param String $editor
	 * @param String $developer
	 * @param Array[int] $genres Array of id's genres
	 * @param Array[int] $platforms Array of id's platforms
	 */
	public function addGame($name, $cover, $releaseDate, $editor, $developer, $idGenres, $idPlatforms)
	{
		$this->set(array('name' => $name, 'cover' => $cover, 'releaseDate' => $releaseDate, 'editor' => $editor,
				'developer' => $developer, 'idGenres' => $idGenres, 'idPlatforms' => $idPlatforms));
		return $this->find("addGame");
	}
}
?>