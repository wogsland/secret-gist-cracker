<?php
$start = time();

if (isset($argv[1])) {
  //get the encoded emails
  $username = $argv[1];

  //setup write file
  if (isset($argv[2])) {
    $filename = $argv[2];
  } else {
    $filename = "gist.txt";
  }
  $solved = fopen($filename,"w");

  $found = 0;
  while ($found == 0) {

    // get the random string to test as a secret gist
    //$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789'; // so ONLY 36^20 possibilities!
    $string = '';
    $random_string_length = 20; //actually gists all seem to have the same length code
    for ($i = 0; $i < $random_string_length; $i++) {
      $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    //$string ="9f331ef9b5bd2241ec82"; //one of my actual gists
    //$string ="9f331ef9b5bd2241ec81"; //not one of my actual gists
    $url = "https://gist.github.com/{$username}/{$string}";
    echo "after ".($sec = time()-$start)." trying $url\n";

    // see if it's on github
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_URL, $url);
    //echo $url."\n";
    $response = curl_exec($ch);
    //echo $response."\n";

    if (strpos($response,"<p>We seem to have missed the <em>gist</em> of that <em>gist</em> you were looking for.</p>") === false) {
      $return = $url;
    }

    if (isset($return)) {
        //echo "in return, url = $url, sec = $sec\n";
        fwrite($solved,"$url $sec\n");
        $found++;
    }
    //echo "found = $found\n";
  }

} else {
  echo "usage:
        php crack.php github_username
        php crack.php github_username secret_gist_destination_file (defaults to gist.txt otherwise)\n";
}
