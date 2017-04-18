<?php
namespace Akaramires\FullContact;

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//function Services_FullContact_autoload($className) {
//	$library_name = 'FullContact';
//
//	if (substr($className, 0, strlen($library_name)) != $library_name) {
//		return false;
//	}
//	$file = str_replace('_', '/', $className);
//	$file = str_replace('Services/', '', $file);
//	return include dirname(__FILE__) . "/$file.php";
//}
//
//spl_autoload_register('Services_FullContact_autoload');

/**
 * This class handles the actually HTTP request to the FullContact endpoint.
 *
 * @package  Services\FullContact
 * @author   Keith Casey <contrib@caseysoftware.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache
 */
class FullContact
{
	const USER_AGENT = 'caseysoftware/fullcontact-php-0.9.0';

	protected $_baseUri = 'https://api.fullcontact.com/';
	protected $_version = 'v2';

	protected $_apiKey = null;

	public $response_obj  = null;
	public $response_code = null;
	public $response_json = null;

	/**
     * The base constructor needs the API key available from here:
     * http://fullcontact.com/getkey
     *
     * @param type $api_key
     */
	public function __construct($api_key)
	{
		$this->_apiKey = $api_key;
	}

	/**
     * This is a pretty close copy of my work on the Contactually PHP library
     *   available here: http://github.com/caseysoftware/contactually-php
     *
     * @author  Keith Casey <contrib@caseysoftware.com>
     * @param   array $params
     * @return  object
     * @throws  FullContactExceptionNotImplemented
     */
	protected function _execute($params = array(), $resource_uri = NULL)
	{
		if(!in_array($params['method'], $this->_supportedMethods)){
			throw new FullContactExceptionNotImplemented(__CLASS__ .
			" does not support the [" . $params['method'] . "] method");
		}

		$params['apiKey'] = urlencode($this->_apiKey);
		if($resource_uri === NULL)
		{
			$fullUrl = $this->_baseUri . $this->_version . $this->_resourceUri .'?' . http_build_query($params);

		}
		else
		{
			$fullUrl = $this->_baseUri . $this->_version . $resource_uri .'?' . http_build_query($params);
			
		}
		
		if ($resource_uri === "/stats.json")
		{
			$cached_json = false;
		}
		else
		{
			$cached_json = $this->_getFromCache($fullUrl);
		}

		// $cached_json = $this->_getFromCache($fullUrl);
		if ( $cached_json !== false )
		{
			$this->response_json = $cached_json;
			$this->response_code = 200;
			$this->response_obj  = json_decode($this->response_json);
		}
		else
		{
			//open connection
			$connection = curl_init($fullUrl);
			curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($connection, CURLOPT_USERAGENT, self::USER_AGENT);
			
			//execute request
			$this->response_json = curl_exec($connection);
			$this->response_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);
			if ( '200' == $this->response_code )
			{
				$this->_saveToCache($fullUrl, $this->response_json);
			}
			$this->response_obj  = json_decode($this->response_json);

			curl_close($connection);

			if ('403' == $this->response_code) {
				throw new ServicesFullContactExceptionNoCredit($this->response_obj->message);
			}
		}

		return $this->response_obj;
	}

	protected function _saveToCache($url, $response_json)
	{
		$cache_path = 'FullContactCache/';
		$cache_file_name = $cache_path.'/'.md5(urldecode($url)).'.json';
		
		return \Storage::put($cache_file_name, $response_json);
	}

	protected function _getFromCache($url)
	{
		$cache_path = 'FullContactCache/';
		$cache_file_name = $cache_path.'/'.md5(urldecode($url)).'.json';

		if ( \Storage::exists($cache_file_name) )
		{
			$json_content = \Storage::get($cache_file_name);
			return $json_content;
		}

		return false;
	}
}