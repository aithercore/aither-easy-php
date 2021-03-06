<?php
/*
AitherEasy-PHP

A simple class for making calls to Aither's API using PHP.
https://github.com/aithercore/aither-easy-php

Tips appreciated: Xbon36F261wXDL4p1CEZAX28t8U4ayR9uu

====================

The MIT License (MIT)

Copyright (c) 2014 Alexandre Devilliers
Copyright (c) 2013 Andrew LeCody

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

====================

// Initialize Aither connection/object
$aither = new \aither\Aitherp('username','password');

// Optionally, you can specify a host and port.
$aither = new \aither\Aitherp('username','password','host','port');
// Defaults are:
//	host = localhost
//	port = 9998
//	proto = http

// If you wish to make an SSL connection you can set an optional CA certificate or leave blank
// This will set the protocol to HTTPS and some CURL flags
$aither->setSSL('/full/path/to/mycertificate.cert');

// Make calls to aitherd as methods for your object. Responses are returned as an array.
// Examples:
$aither->getinfo();
$aither->getrawtransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098',1);
$aither->getblock('000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f');

// The full response (not usually needed) is stored in $this->response while the raw JSON is stored in $this->raw_response

// When a call fails for any reason, it will return FALSE and put the error message in $this->error
// Example:
echo $aither->error;

// The HTTP status code can be found in $this->status and will either be a valid HTTP status code or will be 0 if cURL was unable to connect.
// Example:
echo $aither->status;

*/

namespace aither;
/**
 * @see https://github.com/aithercore/aither-easy-php/blob/master/DOC.md
 * @method array getinfo() Returns an object containing various state info.
 * @method string help(string $command = "") Return all commands
 * @method string sendtoaddress(string $address, double $amount, string $comment = "", string $comment_to = "", bool $subtract_fee_from_amount = false) Send an amount to a given address
 */
class Aitherp {

	// Configuration options
	private $username;

	private $password;

	private $proto;

	private $host;

	private $port;

	private $url;

	private $CACertificate;

	// Information and debugging
	public  $status;

	public  $error;

	public  $raw_response;

	public  $response;

	private $id = 0;

	/**
	 * @param string $username
	 * @param string $password
	 * @param string $host
	 * @param int    $port
	 * @param string $proto
	 * @param string $url
	 */
	public function __construct($username, $password, $host = 'localhost', $port = 40999, $url = null) {
		$this->username = $username;
		$this->password = $password;
		$this->host     = $host;
		$this->port     = $port;
		$this->url      = $url;
		// Set some defaults
		$this->proto         = 'http';
		$this->CACertificate = null;
	}

	/**
	 * @param string|null $certificate
	 */
	public function setSSL($certificate = null) {
		$this->proto         = 'https'; // force HTTPS
		$this->CACertificate = $certificate;
	}

	public function __call($method, $params) {
		$this->status       = null;
		$this->error        = null;
		$this->raw_response = null;
		$this->response     = null;
		// If no parameters are passed, this will be an empty array
		$params = array_values($params);
		// The ID should be unique for each call
		$this->id ++;
		// Build the request, it's ok that params might have any empty array
		$request = json_encode([
			'method' => $method,
			'params' => $params,
			'id'     => $this->id,
		]);
		// Build the cURL session
		$curl    = curl_init("{$this->proto}://{$this->username}:{$this->password}@{$this->host}:{$this->port}/{$this->url}");
		$options = [
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_HTTPHEADER     => ['Content-type: application/json'],
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $request,
			CURLOPT_TIMEOUT        => 60,
		];
		if ($this->proto == 'https') {
			// If the CA Certificate was specified we change CURL to look for it
			if ($this->CACertificate != null) {
				$options[CURLOPT_CAINFO] = $this->CACertificate;
				$options[CURLOPT_CAPATH] = DIRNAME($this->CACertificate);
			} else {
				// If not we need to assume the SSL cannot be verified so we set this flag to FALSE to allow the connection
				$options[CURLOPT_SSL_VERIFYPEER] = false;
			}
		}
		curl_setopt_array($curl, $options);
		// Execute the request and decode to an array
		$this->raw_response = curl_exec($curl);
		$this->response     = json_decode($this->raw_response, true);
		// If the status is not 200, something is wrong
		$this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// If there was no error, this will be an empty string
		$curl_error = curl_error($curl);
		curl_close($curl);
		if (!empty($curl_error)) {
			$this->error = $curl_error;
		}
		if ($this->response['error']) {
			// If aitherd returned an error, put that in $this->error
			$this->error = $this->response['error']['message'];
		} else if ($this->status != 200) {
			// If aitherd didn't return a nice error message, we need to make our own
			switch ($this->status) {
				case 400:
					$this->error = 'HTTP_BAD_REQUEST';
					break;
				case 401:
					$this->error = 'HTTP_UNAUTHORIZED';
					break;
				case 403:
					$this->error = 'HTTP_FORBIDDEN';
					break;
				case 404:
					$this->error = 'HTTP_NOT_FOUND';
					break;
			}
		}
		if ($this->error) {
			return false;
		}
		return $this->response['result'];
	}

	/**
	 * ﻿Get detailed information about in-wallet transaction <txid>
	 *
	 * @param string $txid
	 * @param bool   $include_watch_only
	 *
	 * @return bool
	 */
	public function gettransaction($txid, $include_watch_only = false) {
		$result = $this->__call('gettransaction', [
			$txid,
			$include_watch_only,
		]);
		if (isset($result['fee'])) {
			if ($result['amount'] == 0) {
				//send or receive to yourselve
				foreach ($result['details'] as $detail) {
					if ($detail['category'] == 'send') {
						$result['amount_send'] = abs($detail['amount'] + $detail['fee']);
					} else {
						$result['amount_receive'] = abs($detail['amount']);
					}
				}
			} else {
				//send to external address
				$result['amount_send']    = abs($result['amount'] + $result['fee']);
				$result['amount_receive'] = 0;
			}
		} else {
			//receive from external address
			$result['amount_send']    = 0;
			$result['amount_receive'] = abs($result['amount']);
		}
		return $result;
	}
}
