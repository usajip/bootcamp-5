// Format input to Rupiah (Rp)
function formatRupiah(element) {
    element.addEventListener('input', function(e) {
        let value = this.value.replace(/[^\d]/g, '');
        if (!value) {
            this.value = '';
            return;
        }
        value = parseInt(value, 10).toLocaleString('id-ID');
        this.value = 'Rp ' + value;
    });
    element.addEventListener('focus', function() {
        this.select();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    if (priceInput) {
        formatRupiah(priceInput);
    }
});
