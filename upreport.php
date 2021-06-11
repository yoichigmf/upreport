<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__.'/src/report_bot.php';


use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler('php://stderr', Logger::WARNING));


//$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LineMessageAPIChannelAccessToken'));

//$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('LineMessageAPIChannelSecret')]);

//$sign = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

//$events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);


$json = file_get_contents("php://input");


$contents = json_decode($json, true);


$appname = getenv('APPLICATION_NAME');

date_default_timezone_set('Asia/Tokyo');


$slack_hook_url = getenv('SlackHookURL');

$slack_dist_channel  = getenv('SlackdistChannel');

$map_url = 'https://reportmap.herokuapp.com/';


$map_var = getenv('MapURL');

//  add 20200506
if ( ! empty($map_var)){
    $map_url = $map_var;
}


$speech_apikey = getenv("SPEECHAPIKEY");



$page = 1;
$action ="";

$score = -1;

//  add 20200607
session_set_cookie_params(60 * 5);

session_start();
$sid = session_id();


$spreadsheetId = getenv('SPREADSHEET_ID');
//   書き込み対象シートの名前を取得しておく
$target_sheetname = GetTargetSheetName( $spreadsheetId);

 $log->addWarning("target_sheet  ${target_sheetname}\n");

while(1){
//foreach ($events as $event) {

//  add 20200607   check in mapmodule
  //  ユーザ名の取得
  // $user_name = GetUserName($event );
   
   $json = file_get_contents("php://input");
   
   $contents = json_decode($json, true );
   
   $kind = $contents["kind"];
   

   $log->addWarning("kind =>  ${kind}\n");
  
  




      if ($kind == 1 ) {  //  イメージメッセージの場合) 

        


               // $log->addWarning("image response  ok\n");



            //    $filepath =  upload_contents( 'image' , 'jpg', 'application/octet-stream', $response ,$appname );

                $log->addWarning("image upload   ok\n");

             //   $tst = AddFileLink( $response, $event, $filepath, "image"  );

             







          continue;

        }



       if ($kind == 3 ) {  //  オーディオメッセージの場合  debug

          
/*
                 $filepath =  upload_contents( 'voice' , 'mp4', 'application/octet-stream', $response , $appname );
                 $voicetext = "";
                 //  Speech API key の指定がある場合のみ音声テキスト化する
                if (!empty($speech_apikey )){
                //  mp4 ファイルの保存
                        $tmp4 = make_filename_path( "voice", "mp4" );

                       $fcontents = $response->getRawBody();

                       file_put_contents( $tmp4, $fcontents );


                       $tflc = make_filename_path( "voice", "flac" );


                //  mp4  -> flac への変換
                       shell_exec("ffmpeg -i ${tmp4} -vn -ar 16000 -ac 1 -acodec flac -f flac ${tflc}");

                //  mp4 ファイルの削除

                       unlink( $tmp4 );

                //  flac ファイルのテキスト変換

                       $voicetext = getTextFromAudio( $tflc , $speech_apikey );


                       unlink( $tflc );

              

                $tst =  AddAudioFileLink( $response, $event, $filepath, "voice" ,${voicetext} );

*/



          

          continue;

        }


       if ($kind == 2) {  //  ビデオメッセージの場合


/*
                 $filepath =  upload_contents( 'video' , 'mp4', 'application/octet-stream', $response, $appname );


                 $tst =  AddFileLink( $response, $event, $filepath, "video"  );

     */     


          continue;



        }

    if ($kind == 4) {  //  ファイルメッセージの場合 debug  2019.11.2

         /*
            $fpath = pathinfo($fname);

            $ext = $fpath['extension'];




          $log->addWarning("file name   ${fname}\n");
          $log->addWarning("extention  ${ext}\n");

           $filepath =  upload_contents( 'file' , $ext, 'application/octet-stream', $response ,$appname );



            $tst = AddFileLink( $response, $event, $filepath, "file"  );

*/
          continue;

        }





     if ($kind == 0) {  //  テキストメッセージの場合
            $tgText=$contents["note"];


           //  テキスト１文字目が # の場合はコメントとみなしてスキップする  20190621

           $chktext  = substr( $tgText, 0, 1 );


           if ( strcmp($chktext, "#" ) == 0 ) {



                   $spreadsheetId = getenv('SPREADSHEET_ID');

                    if ( strcmp($tgText, "#map" ) == 0 ) {   //  display map URL

                       //$mhostname = gethostname();

                      // $appname = getenv('HEROKU_APP_NAME');

                       //$mhostname = exec("apps:info -s  \| grep web_url \| cut -d= -f2");

                      // $mhostname = "${appname}.heroku.com";

                      // $bot->replyText($event->getReplyToken(), "地図表示     ${map_url}");   //map urL
                       }

                   if ( strcmp($tgText, "#sheet" ) == 0 ) {   //  display sheet URL

                     //  $bot->replyText($event->getReplyToken(), "集計シート (閲覧)     https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit?usp=sharing");   //sheet urL
                       }
                  if ( strcmp($tgText, "#list" ) == 0 ) {   //  display sheet URL

                    //   $bot->replyText($event->getReplyToken(), "集計シート (閲覧)     https://docs.google.com/spreadsheets/d/${spreadsheetId}/edit?usp=sharing");   //sheet urL
                       }



                    if ( strcmp($tgText, "#help" ) == 0 ) {   //  display help
                    //     displayHelp( $bot, $event );

                       }


                   continue;
                   }

           // $tst = AddText(  $event  );

            

          continue;

        }





       // $bot->replyText($event->getReplyToken(), "その他メッセージ   ");

   }
