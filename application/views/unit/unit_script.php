<script>
    $(document).ready(function(){ 
        
        
    })
    function HapusSat(kode){
        var code = kode
        var url = "<?= base_url('mpersediaan/deleteUnit/') ?>"+kode
        bootbox.confirm('Yakin ingin menghapus Satuan '+ code + ' ?', function(res){
            if (res) {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: url,
                    data:{},
                    success: function () {
                        document.location.href = "<?php echo base_url().'unitListing'; ?>";
                    } 
                })
            }
        })
    }
</script>