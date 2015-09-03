<form method="post" action="/user/login" class="spa_form" spaAfterSubmit="loggedIn">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required="required">
  </div>
    
  <button type="submit" class="btn btn-default">Login</button>
</form>

<script>
    function loggedIn() {
        redirectContentDiv("/timezones");
    }
</script>