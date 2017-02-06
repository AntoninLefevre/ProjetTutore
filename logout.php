<?php

require_once("autoload.inc.php");

User::logout();

header("Location: index.php");
