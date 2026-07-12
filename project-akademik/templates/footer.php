<?php
require_once __DIR__ . '/../config/flash.php';
$flashes = get_flashes();
?>

<!-- Container Toast -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <?php foreach ($flashes as $i => $f): ?>
        <div class="toast align-items-center text-bg-<?= $f['type']; ?> border-0 mb-2"
             role="alert" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    <?= htmlspecialchars($f['message']); ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"></button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Bootstrap JS (wajib untuk toast) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// auto show semua toast yang ada
document.addEventListener("DOMContentLoaded", function () {
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    toastElList.map(function (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    });
});
</script>
