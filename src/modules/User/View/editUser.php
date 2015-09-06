<form method="post" action="/user/<?=$user->getId()?>" class="spa_form" id='editUserForm' spaAfterSubmit="saveOk">
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
    
  <button type="submit" class="btn btn-default">Edit User</button>
</form>

<script>editUser(<?=json_encode($user)?>);
