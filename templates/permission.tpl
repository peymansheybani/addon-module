<div class="row" style="padding: 0px 50px;">
    <div class="col-md-12" style="margin-top: 15px;border: 1px solid #e5e5e5;padding: 15px;border-radius: 5px;">
        <div class="col-md-12">
            <div class="col-md-3">
                <select class="form-control" name="roles" id="roles">
                    <option value="">انتخاب نقش</option>
                    {foreach from=$roles key=key item=role}
                        <option value="{$role->id}">{$role->name}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="col-md-12" style="padding: 0px 40px;margin-top: 20px;">
            {foreach from=$permissions key=key item=perm }
                <label class="checkbox-inline"><input type="checkbox" class="check_perm" name="perm[]" id="{$perm}" value="{$perm}">{$perm}</label>
            {/foreach}
        </div>
        <div class="col-md-12" style="margin-top: 20px;">
            <a class="btn btn-success" id="set_permission">
                ثبت دسترسی
            </a>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#roles').change(function () {
            $(".check_perm"). prop("checked", false);
            $.ajax({
                url : '{$link}&action=admin/getPermission',
                type : 'post',
                data : {
                    role_id : $(this).val()
                },
                success : function (data) {
                    data = JSON.parse(data);
                    data.forEach(function ($value, $key) {
                        $('#'+$value).prop('checked', 'true');
                    });
                }
            });
        });

        $('#set_permission').click(function () {
            var perms = $('input[name="perm[]"]:checked').map(function () {
                return this.value;
            }).get();
            var role_id = $('#roles').val();

            $.ajax({
               url : '{$link}&action=admin/addPermission',
               type : 'post',
               data : {
                   role_id : role_id,
                   perms : perms
               },
               success : function (data) {
                   console.log(data);
               }
            });
        });
    });
</script>