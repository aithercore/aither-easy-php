# Aitherp
By Alexandre (aka elbereth) Devilliers

A simple class for making calls to Dash's RPC API using PHP.

## Getting Started:
1. Include aitherp.php into your PHP script:

	`require_once('aitherp.php');`
2. Initialize Dash connection/object:

	`$aither = new \aither\Aitherp('username','password');`

	Optionally, you can specify a host, port. Default is HTTP on localhost port 9998.

	`$aither = new \aither\Aitherp('username','password','localhost','9998');`

	If you wish to make an SSL connection you can set an optional CA certificate or leave blank
	`$aither->setSSL('/full/path/to/mycertificate.cert');`

3. Make calls to dashd as methods for your object. Examples:

	`$aither->getinfo();`    
	`$aither->getrawtransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098',1);`    
	`$aither->getblock('000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f');`    
	`$aither->mnbudget('show');`    

## Additional Info:
* When a call fails for any reason, it will return false and put the error message in $aither->error

* The HTTP status code can be found in $aither->status and will either be a valid HTTP status code or will be 0 if cURL was unable to connect.

* The full response (not usually needed) is stored in $aither->response while the raw JSON is stored in $aither->raw_response

## Contribution Info

Original code is licenced under MIT.
