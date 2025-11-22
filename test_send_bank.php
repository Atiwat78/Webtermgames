<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จำลองระบบธนาคาร (Admin Test)</title>
    <style>
        body { font-family: sans-serif; padding: 50px; background: #eee; }
        .card { background: white; padding: 20px; border-radius: 8px; width: 400px; margin: 0 auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 10px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        button:hover { background: #0056b3; }
        .result { margin-top: 20px; padding: 10px; background: #f8f9fa; border: 1px solid #ddd; display: none; }
    </style>
</head>
<body>

<div class="card">
    <h2>🏧 จำลองธนาคารส่งยอด</h2>
    <p>ใส่ยอดเงินที่มีเศษสตางค์ เพื่อทดสอบว่าระบบจะเติมให้เต็มจำนวนหรือไม่</p>
    
    <form id="bankForm">
        <label>Secret Key:</label>
        <input type="text" name="secret" value="nty55"> <label>ยอดเงินที่ลูกค้าโอน (เช่น 19.45):</label>
        <input type="number" step="0.01" name="amount" placeholder="0.00" required>
        
        <button type="submit">ยิง Webhook 🚀</button>
    </form>

    <div id="resultBox" class="result"></div>
</div>

<script>
document.getElementById('bankForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const resultBox = document.getElementById('resultBox');

    fetch('webhook.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        resultBox.style.display = 'block';
        resultBox.innerHTML = '<strong>สถานะ:</strong> ' + data.status + '<br>' +
                              '<strong>ข้อความ:</strong> ' + data.message + '<br>' +
                              (data.points_added ? '<strong>✅ เติมเหรียญให้: ' + data.points_added + ' แต้ม</strong>' : '');
        
        if(data.status === 'success') {
            resultBox.style.backgroundColor = '#d4edda';
            resultBox.style.color = '#155724';
        } else {
            resultBox.style.backgroundColor = '#f8d7da';
            resultBox.style.color = '#721c24';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    });
});
</script>

</body>
</html>