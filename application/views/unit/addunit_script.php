<script>
    function ChangeCase(elem) 
    {
        elem.value = elem.value.toUpperCase();
    }  

    $('#simpan').on('click', function () {
        let kode = $('#kode').val()
        let nama = $('#nama').val()
        let aktif = $('#aktif').val()
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('Mpersediaan/addUnit')?>",
            data: {
                kode: kode, nama: nama, aktif: aktif
            },
            success: function (e) {
                console.log(e)
                if (e.status == 'success') {
                    document.location.href = "<?php echo base_url().'unitListing'; ?>";
                } else {
                    console.log('ERROR')
                }
            },
            error: function (e) {
                alert('Proses gagal.')
            }
        })
    })
</script>