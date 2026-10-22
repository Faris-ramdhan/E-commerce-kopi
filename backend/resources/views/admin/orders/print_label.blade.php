<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Pengiriman - {{ $order->order_number }}</title>
    <style>
        @page { size: A6 portrait; margin: 8mm; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #111; margin: 0; padding: 12px; }
        .label-card { border: 2px dashed #b85d38; padding: 14px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #222; padding-bottom: 8px; margin-bottom: 10px; }
        .brand { font-size: 14px; font-weight: bold; color: #b85d38; letter-spacing: 0.5px; }
        .order-badge { background: #222; color: #fff; padding: 3px 8px; font-weight: bold; border-radius: 4px; font-size: 11px; }
        .shipping-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 12px; }
        .section-box { border: 1px solid #ddd; padding: 8px; border-radius: 4px; background: #fafafa; }
        .section-title { font-weight: bold; font-size: 9px; text-transform: uppercase; color: #777; margin-bottom: 4px; }
        .courier-box { background: #fff3ed; border: 1px solid #b85d38; padding: 8px; border-radius: 4px; text-align: center; margin-bottom: 12px; }
        .courier-title { font-size: 14px; font-weight: bold; color: #b85d38; }
        .resi-text { font-family: monospace; font-size: 13px; font-weight: bold; letter-spacing: 1px; margin-top: 2px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 10px; }
        .items-table th, .items-table td { border-bottom: 1px solid #eee; padding: 4px; text-align: left; }
        .items-table th { background: #f0f0f0; }
        .note-box { margin-top: 8px; padding: 6px; background: #fffde7; border-left: 3px solid #fbc02d; font-size: 9px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 14px; background: #b85d38; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Label (Print)
        </button>
    </div>

    <div class="label-card">
        <div class="header">
            <div class="brand">KOPI NUSANTARA ROASTERY</div>
            <div class="order-badge">{{ $order->order_number }}</div>
        </div>

        <div class="courier-box">
            <div class="courier-title">{{ strtoupper($order->courier ?? 'KURIR REGULER') }} - {{ strtoupper($order->courier_service ?? 'STANDARD') }}</div>
            <div class="resi-text">RESI: {{ $order->tracking_number ?? 'BELUM ADA RESI' }}</div>
            <div style="font-size: 10px; color: #555; margin-top: 2px;">Berat Paket: {{ $order->total_weight_grams }} gram</div>
        </div>

        <div class="shipping-meta">
            <div class="section-box">
                <div class="section-title">Penerima (To):</div>
                <div style="font-weight: bold; font-size: 12px;">{{ $order->shipping_name }}</div>
                <div>{{ $order->shipping_phone }}</div>
                <div style="margin-top: 4px;">{{ $order->shipping_address }}</div>
                <div>{{ $order->shipping_district ? $order->shipping_district . ', ' : '' }}{{ $order->shipping_city }}</div>
                <div>{{ $order->shipping_province }} {{ $order->shipping_postal_code }}</div>
            </div>

            <div class="section-box">
                <div class="section-title">Pengirim (From):</div>
                <div style="font-weight: bold;">Kopi Nusantara Roastery</div>
                <div>0812-3456-7890</div>
                <div style="margin-top: 4px;">Sentra Roasting Nusantara, Dago</div>
                <div>Kota Bandung, Jawa Barat 40132</div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk / Varian</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Berat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong>
                            @if ($item->variant_name)
                                <div style="color: #666; font-size: 9px;">Varian: {{ $item->variant_name }}</div>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ $item->weight_grams * $item->quantity }}g</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($order->customer_note)
            <div class="note-box">
                <strong>Catatan Seduh / Gilingan:</strong> {{ $order->customer_note }}
            </div>
        @endif
    </div>
</body>
</html>
