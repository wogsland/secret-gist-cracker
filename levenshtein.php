<?php
/**
 * This calculates the average Levenshtein distance between the username and the gist.
 * Runs once a minute to insure new gists in each sample & because that is github's unauthenticated rate limit.
 */

if (isset($argv[1]) && is_numeric($argv[1])) {
  $loops = $argv[1];

  // open the file to write to/read from
  $distances = fopen("levenshtein_distance.txt","a+");

  for ($i=1;$i<=$loops;$i++){
    // grab the most recent public gists from github
    $ch = curl_init();
    $header = array("User-Agent: secret-gist-cracker");
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_URL, "https://api.github.com/gists/public");
    $response = curl_exec($ch);

    // calculate and store the information
    $gists = json_decode(strstr($response,"[{"));
    foreach ($gists as $gist) {
      $id = $gist->id;
      $username = $gist->owner->login;
      fwrite($distances, $id."|".$username."|".levenshtein($id,$username)."\n");
    }
    echo "Found ".count($gists)." in pass $i\n";

    sleep(60); // so as not to hit github's rate limit
  }

  // average Levenshtein distances
  rewind($distances);
  $count = 0;
  $sum = 0;
  while ($line = fgets($distances)) {
    $bits = explode("|",$line);
    $sum += $bits[2];
    $count++;
  }
  echo "Average Levenshtein distance of ".((float)$sum)/$count." in $count examples\n";

} else {
  echo "usage:
        php levenshtein.php number_of_loops_to_run\n";
}
