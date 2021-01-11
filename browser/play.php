<?php

// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Flags</title>
    <meta charset="UTF-8">
    <meta name="description" content="Flags">
    <meta name="keywords" content="Flags, Game">
    <meta name="author" content="Marin Mema">

    <style type="text/css">
    body {
      background-color: #feecd6;
    }
    audio {
      display: none;
    }
    #container {
      width: max-content;
      margin: 20px auto 0 auto;
      font-family: arial;
    }
    #noscript {
      display: block;
      padding: 10px;
      text-align: center;
      font-weight: bold;
      background-color: pink;
      border: 1px solid tomato;
    }
    #account-bar, #status-bar {
      display: block;
      /*
      border: 3px solid #f6d084;
      box-shadow: 0 0 0 3px #964d0c;
      border-radius: 5px;
      background-color: #ffffff;
      padding: 0.5em;
      */
      text-align: right;
    }
    #player-name {
        background-color: #fffcf6;
        border: 2px solid #f9b236;
        width: auto;
        display: inline-block;
        border-radius: 5px;
        padding: 0.3em 0.5em 0.3em 0.5em;
        font-size: 10pt;
        text-align: center;
        font-weight: bold;
        color: #8c480e;
    }
    #logout-button {
      background-color: #d88f45;
      border: 2px solid #734014;
      color: #fbf0d8;
      width: auto;
      display: inline-block;
      border-radius: 5px;
      padding: 0.3em 0.5em 0.3em 0.5em;
      font-size: 10pt;
      text-align: center;
      font-weight: bold;
      text-decoration: none;
    }
    #board-container {
      display: block;
      margin: 1em auto 1em auto;
      border: 3px solid #f6d084;
      box-shadow: 0 0 0 3px #964d0c;
      border-radius: 5px;
      box-sizing: border-box;
      overflow: hidden;
    }
    canvas {
      display: block;
    }
    #note-box {
      display: inline-block;
      float: left;
      padding: 10px;
      text-align: center;
      font-weight: bold;
      width: 67%;
      border-radius: 8px;
      box-sizing: border-box;
      margin-right: 0%;
    }
    .disconnected-status {
      background-color: pink;
      border: 2px solid tomato;
    }
    .waiting-status {
      background-color: aliceblue;
      border: 2px solid cornflowerblue;
    }
    .ready-status {
      background-color: yellowgreen;
      border: 2px solid seagreen;
    }
    .your-turn-status {
      background-color: aliceblue;
      border: 2px solid cornflowerblue;
    }
    .opponents-turn-status {
      background-color: aliceblue;
      border: 2px solid cornflowerblue;
    }
    .busy-status {
      background-color: lightgray;
      border: 2px solid silver;
    }
    .opponent-disconnected-status {
      background-color: cornsilk;
      border: 2px solid chocolate;
    }
    .winner-status {
      background-color: yellowgreen;
      border: 2px solid seagreen;
    }
    #turn-score-container {
      text-align: center;
      display: inline-block;
      float: right;
      width: 30%;
      overflow: hidden;
      border: 2px solid #964d0c;
      border-radius: 8px;
      box-sizing: border-box;
      /*box-shadow: 3px 3px 0 0 #666666;*/
    }
    .not-playing > .score-box {
      background-color: #dddddd;
      color: #cccccc;
    }
    .score-box {
      display: inline-block;
      width: 50%;
      box-sizing: border-box;
      padding: 8px 0 5px 0;
      font-family: arial;
      font-size: 12pt;
      font-weight: bold;
      text-align: center;
      color: #ffffff;
    }
    .active-turn {
      background-color: yellow;
    }
    img {
      display: inline;
    }
    #player-0-score-box {
      border-bottom: 5px solid #1570f9;
      background-color: #379ad1;
    }
    #player-1-score-box {
      border-bottom: 5px solid #b10300;
      background-color: #db594f;
    }
    .not-playing > #player-0-score-box {
      border-bottom: 5px solid #999999;
      background-color: #bbbbbb;
    }
    .not-playing > #player-0-score-box > .remaining {
      color: #999999;
    }
    .not-playing > #player-1-score-box > .remaining {
      color: #999999;
    }
    .not-playing > #player-1-score-box {
      border-bottom: 5px solid #999999;
      background-color: #bbbbbb;
    }
    .not-playing > #player-0-score-box > #player-0-score {
      color: #999999;
    }
    .not-playing > #player-1-score-box > #player-1-score {
      color: #999999;
    }
    .not-playing > .remaining {
      color: #999999;
    }
    #player-0-score, #player-1-score {
      display: inline-block;
      text-align: right;
      color: #ffffff;
    }
    .score-box-winner {
      background-color: #aed581;
      border-bottom: 3px solid green;
      color: green;
    }
    #player-0-score-box > .remaining {
      color: #0047b1;/*#1570f9;*/
    }
    #player-1-score-box > .remaining {
      color: #920604;/*#b10300;*/
    }
    .score-box-winner > .remaining {
      color: green;
    }
    #whose-turn {
      color: black;
      font-size: 80%;
      display: block;
      background-color: #cccccc;
      padding: 5px;
      display: none;
    }
    #autoplay-indicator {
      display: block;
      visibility: hidden;
      color: green;
      margin-top: 10px;
      font-size: 50%;
      font-weight: bold;
    }
    </style>
  </head>
  <body>
    <div id="container">
      <!--
        Will need this later...
        <div id="online-count-container">
          <span id="online-indicator">&#8226;  </span><span id="online-count"></span>
        </div>
      -->
      <noscript><div id="noscript">This game uses JavaScript.</div></noscript>
      
      <div id="account-bar">
        <div id="player-name"><?php echo $_SESSION["p_name"]; ?></div>
        <a href="logout.php" id="logout-button">Logout</a>
      </div>
      
      <div id="board-container"></div>

      <div id="status-bar">
        <div id="note-box">Welcome to Flags</div><!--
        --><div id="turn-score-container" class="not-playing">
          <div id="whose-turn">...</div>
          <div class="score-box" id="player-0-score-box">
            <div id="player-0-score">0</div> <span class="remaining">0</span>
          </div><!--
        --><div class="score-box" id="player-1-score-box">
            <div id="player-1-score">0</div> <span class="remaining">0</span>
          </div>
        </div>
        <!--<div id="autoplay-indicator">AUTOPLAY ON</div>-->
      </div>
    </div>

    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="config.js"></script>
    <script type="text/javascript" src="flags-canvas.js"></script>
    <script type="text/javascript" src="flags-events.js"></script>
    <script type="text/javascript" src="page.js"></script>

    <audio loop id="game-music">
      <source src="nintendo-dsi-shop-music.mp3" type="audio/mp3">
    Your browser does not support the audio element.
    </audio>
  </body>
</html>