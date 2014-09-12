Secret Gist Cracker
===================

_An exercise in futility..._

[![Code Climate](https://codeclimate.com/github/wogsland/secret-gist-cracker/badges/gpa.svg)](https://codeclimate.com/github/wogsland/secret-gist-cracker)

__The Problem__

Executing a curl request per second on a 20 character string with 36 possibilities per character
will take roughly a mole of years to cycle through all the possibilities. That is,

    (36^20)/60/60/24/365.25 ~ 6*10^23

making it unlikely to guess anyone's secret gist in the lifetime of the universe by brute force. Seems pretty secure, right?
Try it yourself for a few minutes/hours/centuries/Gy:

    $> php brute_force_crack.php wogsland

__Random?__

Calculating the Levenshtein distance on ~30,000 gists yields an average distance of 18.88 between
the github username and the gist identifier. To verify for yourself, try

    $> php levenshtein.php 1000

Of course, to find a secret gist we don't actually need to know the username...
