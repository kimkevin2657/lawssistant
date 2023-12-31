<?php
/*
 * login_with_google.php
 *
 * @(#) $Id: login_with_google.php,v 1.7 2013/07/31 11:48:04 mlemos Exp $
 *
 */

	/*
	 *  Get the http.php file from http://www.phpclasses.org/httpclient
	 */
	include_once('./_common.php');
	require('http.php');
	require('oauth_client.php');

	$client = new oauth_client_class;
	$client->server = 'Google';

	// set the offline access only if you need to call an API
	// when the user is not present and the token may expire
	$client->offline = true;

	$client->debug = false;
	$client->debug_http = true;
	$client->redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].
		dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_google.php';

	$client->client_id = $google_ClientID; $application_line = __LINE__;
	$client->client_secret = $google_ClientSecret;

	if(strlen($client->client_id) == 0
	|| strlen($client->client_secret) == 0)
		alert_close('구글 연동키값을 확인해 주세요.');
		/*
		die('Please go to Google APIs console page '.
			'http://code.google.com/apis/console in the API access tab, '.
			'create a new client ID, and in the line '.$application_line.
			' set the client_id to Client ID and client_secret with Client Secret. '.
			'The callback URL must be '.$client->redirect_uri.' but make sure '.
			'the domain is valid and can be resolved by a public DNS.');
		*/
	/* API permissions
	 */
	$client->scope = 'https://www.googleapis.com/auth/userinfo.email '.
		'https://www.googleapis.com/auth/userinfo.profile';
	if(($success = $client->Initialize()))
	{
		if(($success = $client->Process()))
		{
			if(strlen($client->authorization_error))
			{
				$client->error = $client->authorization_error;
				$success = false;
			}
			elseif(strlen($client->access_token))
			{
				$success = $client->CallAPI(
					'https://www.googleapis.com/oauth2/v2/userinfo',
					'GET', array(), array('FailOnAccessError'=>true), $user);
			}
		}
		$success = $client->Finalize($success);
	}
	if($client->exit)
		exit;
	if($success)
	{
		$client->GetAccessToken($AccessToken);

		$mb_gubun = 'g_';
		$mb_id = $user->id;
		$mb_name = $user->name;
		$mb_nick = $user->name;
		$mb_email = $user->email;
		$token_value = $AccessToken['value'];
		$token_secret = '';
		$token_refresh = $AccessToken['refresh'];

		//$client->ResetAccessToken();

		include_once('./oauth_check.php');
	} else {
		$error = HtmlSpecialChars($client->error);
		alert_close($error);
	}
?>
