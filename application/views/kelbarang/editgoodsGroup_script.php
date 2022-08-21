<script>
    $(document).ready(function() {
        $('#simpan').on('click', function () {
            var kode = $('#kode').val()
            var inisial = $('#inisial').val()
            var nama = $('#nama').val()
            var tipe = $('#tipe').val()
            var aktif = $('#aktif').val()

            $.ajax({
                type: "post",
                url: "<?php echo base_url().'Mpersediaan/editGoodsGroup_'?>",
                data: {kode: kode, nama : nama, inisial : inisial, tipe : tipe, aktif : aktif},
                dataType: "json",
                cache: false,
                success: function(data){
                    console.log(data)
                    document.location.href = "<?php echo base_url().'goodsGroupListing'; ?>";
                }
            });
        })
    })
</script>