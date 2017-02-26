<?php

include_once("classes/User.class.php");

User::startSession();

header("Content-type: image/png");
User::captcha();
