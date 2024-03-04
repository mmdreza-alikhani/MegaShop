<style>
    .back_to_top_btn {
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 2;
        opacity: 0;
        transition: all .4s ease
    }
</style>

<a href="#" class="back_to_top_btn">
    <i class="fa fa-2x fa-chevron-circle-up"></i>
</a>

<script>
    let back_to_top_btn = document.querySelector('.back_to_top_btn');

    window.addEventListener('scroll', function () {
        if(window.scrollY > 100){
            back_to_top_btn.style='opacity: 1'
        }else{
            back_to_top_btn.style='opacity: 0'
        }
    })
</script>
