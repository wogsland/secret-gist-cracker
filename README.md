Secret Gist Cracker
===================

_An exercise in futility._

__The Problem__

Executing a curl request per second on a 20 character string with 36 possibilities per character
will take roughly a mole of years to cycle through all the possibilities. That is,

    (36^20)/60/60/24/365.25 ~ 6*10^23

making it unlikely to guess a gist in the lifetime of the universe by brute force. Seems pretty secure, right?
