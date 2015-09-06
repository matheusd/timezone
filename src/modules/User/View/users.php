<h1>User Listing</h1>
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
                    <button type="button" class='btn btn-xs btnListUserTz'><i class="glyphicon glyphicon-list"></i></button>
                    <button type="button" class='btn btn-xs btnEditUser'><i class="glyphicon glyphicon-edit"></i></button>
                    <button type="button" class='btn btn-xs btnDelUser'><i class="glyphicon glyphicon-remove"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<h2>New User</h2>

<form method="post" action="/users" class="spa_form" id='editUserForm' spaAfterSubmit="newUserCreated">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" placeholder="User's Name" name="name" >
  </div>
  <div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required="required">
  </div>
  <div class="form-group">
    <label for="role">Role</label>
    <select id='role' name='role' class='form-control'>
        <option value='0'>User</option>
        <?php if ($currentUser->getRole() > 1) echo "<option value='1'>Manager</option>"; ?>
        <?php if ($currentUser->getRole() == 999) echo "<option value='999'>Admin</option>"; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword2">Repeat Password</label>
    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Repeat Password" name="password2">
  </div>

  <button type="submit" class="btn btn-default">Create User</button>
</form>


<script>reloadUsers(<?=json_encode($users)?>);</script>