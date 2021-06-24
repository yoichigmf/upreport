<?php

require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__.'/src/report_bot.php';


use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));




if (count($_POST) ==0 ) {

	http_response_code( 400 );
	 $log->warning("no args");
	
	exit;
}
else {

	session_start();

        $token_str = $_POST["token"];
        $cmd =   $_POST["command"];
        
        $log->warning("token  ${token_str}\n");
        $log->warning("cmd  ${cmd}\n");

	if ( $_POST["command"] == "START" ) {
        # check token

               $url = 'https://api.line.me/oauth2/v2.1/verify?access_token='.$token_str;
	       $session = curl_init();
	       curl_setopt($session, CURLOPT_URL, $url );
	       curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	       $r = curl_exec($session);

	       curl_close($session);

	       $res = json_decode($r);


	       if ( $res->error ) {
                   http_response_code( 401 );
                   print("unauthorized\n");
		       print "error";
		   exit;
	       }

               $nowt = new DateTime("now");
	       if (  $res->expires_in < $nowt ){

                            //  expire 
	       }

               http_response_code( 200 );
                print("START \n");
	       #
	       print $r;
	       #
	       print $res->expires_in;
	       #O
        	$_SESSION["token"] = $token_str;
	         exit;

	}

	#   token check
	if (  strcmp($token_str, $_SESSION["token"]) == 0  ) {
                   http_response_code( 401 );
                   $log->warning("unauthorized token is not same ${token_str}\n");
                  $log->warning("session token  ". $_SESSION["token"] );
                   print("unauthorized\n");
                   exit;
	       }

	if ( $_POST["command"] == "DATA" ) {
        # check token



	#  data regist
		#
		#
               http_response_code( 200 );
                print("DATA\n");
                print("token " . $token_str);
                print("\n session token " . $_SESSION["token"]);
#
	         exit;
	}

	if ( $_POST["command"] == "END" ) {
               http_response_code( 200 );
        	unset($_SESSION["token"]);
	        session_destroy();
                print("END\n");

		}


       print( "command " .  $_POST["command"]);	
	exit;
}

// JSON文字列をobjectに変換
//   ⇒ 第2引数をtrueにしないとハマるので注意

//
// デバッグ用にダンプ
#
print "OK";
#var_dump($contents);
?>
