<script>
    $(document).ready(function() {
        $('#simpan').on('click', function () {
            var kode = $('#kode').val() 
            var nama = $('#nama').val()
            var aktif = $('#aktif').val()

            $.ajax({
                type: "post",
                url: "<?php echo base_url().'Mpersediaan/editbrand'?>",
                data: {kode: kode, nama : nama, aktif : aktif},
                dataType: "json",
                cache: false,
                success: function(data){
                    console.log(data)
                    document.location.href = "<?php echo base_url().'brandListing'; ?>";
                }
            });
        })
    })
</script>