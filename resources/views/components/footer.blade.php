<footer class='p-5 border-t bg-pink-100'>
    <div class="text-center">
        <div class="container mx-auto flex justify-between items-center text-sm text-center">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}™. All Rights Reserved.</span>
            <span>Made by Ichsan Hanifdeal</span>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Apa anda Yakin?',
            text: "anda akan keluar dari sesi login?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
