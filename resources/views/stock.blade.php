<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Market Data</title>
</head>
<body>
<h1>Stock Market Data</h1>
<form id="stockForm">
    <input type="text" id="stockSymbol" placeholder="Enter stock symbol" required>
    <button type="submit">Get Stock Data</button>
</form>
<div id="stockData"></div>

<script>
    document.getElementById('stockForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const symbol = document.getElementById('stockSymbol').value;
        fetch(`/stock/${symbol}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('stockData').innerHTML = JSON.stringify(data, null, 2);
            })
            .catch(error => {
                document.getElementById('stockData').innerHTML = 'Error fetching data';
            });
    });
</script>
</body>
</html>
