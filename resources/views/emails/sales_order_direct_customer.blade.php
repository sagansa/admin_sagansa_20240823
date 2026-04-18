<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Anda Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #2196F3; border-bottom: 2px solid #eee; padding-bottom: 10px;">
            Terima Kasih Atas Pesanan Anda!
        </h2>
        
        <p>Halo <strong>{{ $order->orderedBy?->name ?? 'Pelanggan' }}</strong>,</p>
        
        <p>Pesanan Anda telah berhasil kami terima dan saat ini sedang dalam proses. Berikut adalah ringkasan transaksinya:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee; width: 40%;"><strong>Order ID:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">#{{ $order->id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Tanggal Pesanan:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee;">{{ \Carbon\Carbon::parse($order->created_at)->format('d F Y, H:i') }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Total Pembayaran:</strong></td>
                <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold; color: #4CAF50;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <p style="margin-top: 25px;">
            Kami akan segera memproses dan mengatur pengiriman pesanan Anda. Jika Anda memiliki pertanyaan lebih rinci, jangan ragu untuk membalas email ini.
        </p>

        <p style="font-size: 0.9em; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px;">
            Salam Hangat,<br>
            Tim Asa Pangan Bangsa
        </p>
    </div>
</body>
</html>
