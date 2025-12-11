<!DOCTYPE html>
<html>
<head>
    <?php include 'head.php'; ?>
    <title>Simple Basket</title>
</head>
<body>

<h2>Products</h2>

<div>
    <p>Apple - $1</p>
    <button onclick="addToBasket('apple')">Add to basket</button>
</div>

<div>
    <p>Banana - $2</p>
    <button onclick="addToBasket('banana')">Add to basket</button>
</div>

<script>
function addToBasket(item) {
    fetch('basket.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=add&item=' + item
    })
    .then(r => r.json())
    .then(updateBasket);
}

function loadBasket() {
    fetch('basket.php?action=get')
        .then(r => r.json())
        .then(updateBasket);
}

function updateBasket(data){
    // update basket in page
    document.getElementById("basket").innerHTML = data.html;

    // update header bubble
    let bubble = document.getElementById("basketCount");
    if (bubble) bubble.textContent = data.count;
}

</script>

</body>
</html>
