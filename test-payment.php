<!DOCTYPE html>
<html>
<head>
    <title>prepare-payment.php Test</title>
</head>
<body>
    <h2>prepare-payment.php Test</h2>
    <button onclick="testPreparePayment()">Test Et</button>
    <button onclick="testWithRealData()">Gerçek Veri ile Test Et</button>
    <div id="result"></div>

    <script>
    function testPreparePayment() {
        fetch('/cinaralti-website/includes/actions/prepare-payment.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({test: true})
        })
        .then(response => {
            console.log('Status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Response:', text);
            document.getElementById('result').innerHTML = '<h3>Test Sonucu:</h3><pre>' + text + '</pre>';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerHTML = '<h3>Hata:</h3>' + error.message;
        });
    }

    function testWithRealData() {
        const testData = {
            cart: [{
                donorName: 'Test User',
                donorEmail: 'test@test.com',
                donorPhone: '05551234567',
                donorType: 'Bireysel',
                title: 'Test Bağış',
                amount: '₺100'
            }],
            totalAmount: 100
        };

        fetch('/cinaralti-website/includes/actions/prepare-payment.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(testData)
        })
        .then(response => {
            console.log('Status:', response.status);
            return response.text();
        })
        .then(text => {
            console.log('Response:', text);
            document.getElementById('result').innerHTML = '<h3>Gerçek Veri Test Sonucu:</h3><pre>' + text + '</pre>';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerHTML = '<h3>Hata:</h3>' + error.message;
        });
    }
    </script>
</body>
</html> 