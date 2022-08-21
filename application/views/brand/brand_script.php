<script>
    function HapusBrand(kode, namabrg){ 
        var code = kode
        var nama = namabrg
        var url = "<?= base_url('mpersediaan/deleteBrand/') ?>"+kode
        bootbox.confirm('Yakin ingin menghapus Brand '+ nama + ' ?', function(res){
            if (res) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: url,
                    data:{},
                    success: function () {
                    } 
                })
                document.location.href = "<?php echo base_url().'brandListing'; ?>";
            }
        })
    }
</script>