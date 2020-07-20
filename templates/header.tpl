<style>
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        right: 100%;
        margin-top: -1px;
    }

    div.dropdown {
        float: right;
        margin-right: 2px;
    }
</style>

<div class="row">
    {menu data=$menu}
</div>

<script>
    $(document).ready(function(){
        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });
    });
</script>
