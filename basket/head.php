<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Minimal Basket Panel</title>
<style>
  body { 
    margin:0; 
  }

  header { display:flex; justify-content:space-between; padding:10px; 
    background:rgba(7, 15, 88, 0.3);}

  #panelBackdrop {
    position:fixed; inset:0;
    background:rgba(0,0,0,0.3);
    opacity:0;
    pointer-events:none;
    transition:opacity .25s;
  }
  #panelBackdrop.visible {
    opacity:1;
    pointer-events:auto;
  }

  /* Basket panel */
  #basketPanel {
    position:fixed; top:0; right:0;
    width:300px; height:100vh;
    background:#fff;
    transform:translateX(100%);
    transition:transform .35s;
    overflow:auto;
  }
  #basketPanel.open {
    transform:translateX(0);
  }

  /* Close button */
  #closePanel { cursor:pointer; float:right; }
</style>
</head>
<body>

<header>
  <span>My Shop</span>
  <span onclick="openBasket()" style="cursor:pointer;">Basket <span id="basketCount">0</span></span>
</header>

<!-- Backdrop -->
<div id="panelBackdrop" onclick="closeBasket()"></div>

<!-- Basket Panel -->
<div id="basketPanel">
  <span id="closePanel" onclick="closeBasket()">✖</span>
  <h3>Your Basket</h3>
  <div id="basket"></div>
</div>

<script>
function openBasket(){
  document.getElementById("basketPanel").classList.add("open");
  document.getElementById("panelBackdrop").classList.add("visible");
}

function closeBasket(){
  document.getElementById("basketPanel").classList.remove("open");
  document.getElementById("panelBackdrop").classList.remove("visible");
}

/* Update basket */
function updateHeaderCount(n){
  document.getElementById("basketCount").textContent = n;
}

function updateBasket(data){
  document.getElementById("basket").innerHTML = data.html;
  updateHeaderCount(data.count);

  document.getElementById('basket').addEventListener('click', function(e){
    if(e.target.classList.contains('add-btn')){
      updateItem(e.target.dataset.item, 'add');
    } else if(e.target.classList.contains('decrease-btn')){
      updateItem(e.target.dataset.item, 'decrease');
    }
  });
}

/* AJAX calls */
function updateItem(item, action){
  const formData = new FormData();
  formData.append('item', item);
  formData.append('action', action);

  fetch('basket.php', { method:'POST', body:formData })
    .then(r => r.json())
    .then(updateBasket);
}

/* Load basket on page load */
function loadBasket(){
  fetch('basket.php?action=get')
    .then(r => r.json())
    .then(updateBasket);
}

loadBasket();
</script>
</body>
</html>
