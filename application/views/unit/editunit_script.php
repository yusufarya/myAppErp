<script>
    $('input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){
            var cek = 'on'
            // console.log(cek)
        } else if($(this).prop("checked") == false){
            var cek = 'off'
        } 
    });
     
    $('#simpan').on('click', function () {
        if($('input[type="checkbox"]').prop("checked") == true){
            var cek = 'on'
            // console.log(cek)
        } else if($('input[type="checkbox"]').prop("checked") == false){
            var cek = 'off'
        }
        let where = $('#code').val()
        let kode = $('#kode').val()
        let nama = $('#nama').val()
        let aktif = cek
        bootbox.confirm('Yakin anda ingin menyimpan?', function(res){
            if (res) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "<?= base_url('mpersediaan/editUnit') ?>",
                    data: {
                        kode: kode, nama: nama, aktif: aktif, where:where
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            document.location.href = "<?php echo base_url().'unitListing'; ?>";
                        } else {
                            alert('ERROR')
                        }
                    }
                })
            }
        })
    })
</script>