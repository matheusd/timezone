<div>
    <table class="table table-bordered" id="userListing">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div style="display: none">
    <table>
        <tbody>
            <tr id="modelUserRow">
                <td class="id"></td>
                <td class="name"></td>
                <td class="role"></td>
                <td class="actions">
                    <button type="button" class='btn btn-xs btnDelUser'><i class="glyphicon glyphicon-remove"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>reloadUsers(<?=json_encode($users)?>);</script>