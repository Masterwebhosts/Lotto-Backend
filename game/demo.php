<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Demo Lotto Game</title>
  <link rel="stylesheet" href="assets/game.css">
</head>
<body>
  <div class="wrapper">
    <div class="hud">
      <div class="title">🎲 Demo Game</div>
      <div class="balance">Balance: $<span id="balance">20.00</span></div>
    </div>

    <div class="attempts">
      Attempts Left: <b id="attempts">10</b>
    </div>

    <div class="wheels">
      <div class="wheel">-</div>
      <div class="wheel">-</div>
      <div class="wheel">-</div>
    </div>

    <div class="controls">
      <input id="n1" type="number" min="0" max="9" placeholder="0">
      <input id="n2" type="number" min="0" max="9" placeholder="0">
      <input id="n3" type="number" min="0" max="9" placeholder="0">
      <input id="mult" type="number" min="1" value="1">
    </div>

    <button class="play" onclick="play()">Play Now</button>
    <div class="msg" id="msg"></div>
  </div>

  <script src="assets/game.js"></script>
</body>
</html>
