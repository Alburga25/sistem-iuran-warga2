<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Pembayaran untuk:
                <span class="text-blue-600">{{ $tagihan->iuran->nama }}</span>
            </h2>
            <p class="text-lg text-gray-700 mb-4">
                Jumlah yang akan dibayar:
                <span class="font-semibold text-green-600">
                    Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}
                </span>
            </p>

            <div class="flex justify-center">
                <button id="pay-button"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pembayaran Anda telah berhasil diproses.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = document.referrer ? document.referrer :
                            "{{ route('tagihan.index') }}";
                    });
                },
                onPending: function(result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran Anda masih menunggu konfirmasi.',
                    });
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal!',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                    });
                },
                onClose: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Dibatalkan',
                        text: 'Anda menutup popup tanpa menyelesaikan pembayaran.',
                    }).then(() => {
                        window.location.href = document.referrer ? document.referrer :
                            "{{ route('tagihan.index') }}";
                    });
                }
            });
        });
    </script>

    <!-- Tambahkan library SweetAlert2 untuk notifikasi yang lebih modern -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>
