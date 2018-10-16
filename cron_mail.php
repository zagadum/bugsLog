<?php
include "./lib/config.php";
include "./lib/classes/Db.php";
include "./lib/classes/Template.php";
include "./lib/classes/mail/class.phpmailer.php";
DB_connect();
$view = new Template();
$now=date('Y-m-d 00:00:00',strtotime("-10 day"));
$sql = "SELECT * from bugs where   `las_seen`>'$now'  AND rule_id=0 LIMIT 100";
$rs = db_query($sql);
$report=array();
while ($row = db_fetch($rs)) {

    $report[]=$row;

}
if (empty($report)){
   //  print 'not have info';die;
}
$view->report=$report;
$view->server='http://'.$_SERVER['SERVER_NAME'];
$Body=  $view->render('admin/template/mail_report.php');

$mail = new PHPMailer();

$mail->IsSMTP();                    // send via SMTP
$mail->SetLanguage('en','lib/classes/mail/language/');
 
$mail->SMTPAuth = true;
$mail->SMTPDebug = 4;

 $mail->SMTPSecure = false;
$mail->SMTPAutoTLS = false;
 //$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587; 
 $mail->Username = 'zagadum@notan.ua';
 $mail->Password = 'zagadum4519';

$mail->Subject = 'bugtracker.awery.com on Date:'.date('d-m-Y');
$mail->From=$mail->Username;
$mail->FromName =$mail->Username;
$mail->Body=$Body;
$mail->WordWrap = 50;
$mail->From     =  $mail->Username;
//$mail->FromName = 'bugtracker.awery.com';
$ToName='zagadum';
$To='zagadum@notan.ua';
$mail->AddAddress($To , $ToName);
$mail->AltBody  =  "This is the text-only body";

$mail->IsHTML(true);
//$mail->CharSet="utf-8";
//$mail->AltBody(strip_tags($message));
if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else
{
    echo "Message sent!";
}
?>