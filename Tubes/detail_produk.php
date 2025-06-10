<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        .breadcrumb {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .product-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .product-subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 15px;
        }
        .sales-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        .price {
            font-size: 24px;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 20px;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
        }
        .tab.active {
            border-bottom: 2px solid #e91e63;
            color: #e91e63;
            font-weight: bold;
        }
        .product-detail {
            margin-bottom: 20px;
        }
        .detail-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .product-description {
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .spec-item {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .delivery-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
            font-size: 14px;
        }
        .delivery-item {
            margin-bottom: 10px;
        }
        .action-buttons {
            display: flex;
            margin-top: 20px;
            gap: 10px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #e91e63;
            color: white;
            border: none;
        }
        .btn-secondary {
            background-color: white;
            color: #e91e63;
            border: 1px solid #e91e63;
        }
        .quantity-selector {
            margin-bottom: 20px;
        }
        .subtotal {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .social-actions {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="breadcrumb">Home > Komputer & Laptop > Aksesoris PC Gaming > Keyboard Keycaps &...</div>
    
    <h1 class="product-title">GATERON BLUE G PRO 3.0 (LUBED)</h1>
    <div class="product-subtitle">Mechanical Keyboard Switch</div>
    
    <div class="sales-info">Terjual 6 • 💤 5 (1 rating)</div>
    
    <div class="price">Rp5.000</div>
    
    <div class="tabs">
        <div class="tab active">Detail</div>
        <div class="tab">Spesifikasi</div>
        <div class="tab">Info Penting</div>
    </div>
    
    <div class="product-detail">
        <div class="detail-item">
            <span class="detail-label">Kondisi:</span>
            <span>Baru</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Min. Pemesanan:</span>
            <span>1 Buah</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Etalase:</span>
            <span>Semua Etalase</span>
        </div>
    </div>
    
    <div class="product-description">
        <p>GATERON BLUE G PRO 3.0 (LUBED) Mechanical Keyboard Switch</p>
        <p>sudah LUBED dari pabrik Upgraded di sisi Backlit menjadi frosty dan Pin yang lebih kuat dan konsisten.</p>
    </div>
    
    <div class="spec-item">Actuation 60g</div>
    <div class="spec-item">Pre Travel 2.3±0.6 mm</div>
    
    <div class="delivery-info">
        <div class="delivery-item">✓ Pengiriman dari Kota Administrasi Jakarta Barat</div>
        <div class="delivery-item">✓ Ongkir mulai Rp6.500</div>
        <div class="delivery-item">Regular • Estimasi tiba 12 - 13 Jun</div>
    </div>
    
    <div class="quantity-selector">
        <label for="quantity">Atur jumlah dan catatan</label><br>
        <input type="number" id="quantity" name="quantity" min="1" max="991" value="1">
        <span>Stok Total: 991</span>
    </div>
    
    <div class="subtotal">Subtotal Rp5.000</div>
    
    <div class="action-buttons">
        <button class="btn btn-primary">Beli Langsung</button>
        <button class="btn btn-secondary">+ Keranjang</button>
        <button class="btn btn-secondary">Chat</button>
    </div>
    
    <div class="social-actions">
        <div>☐ Wishlist</div>
        <div>Share</div>
    </div>
</body>
</html>