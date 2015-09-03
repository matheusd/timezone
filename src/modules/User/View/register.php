<form method="post" action="/user/new" class="spa_form" spaAfterSubmit="registrationComplete">
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" placeholder="Your Name" name="name" >
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email" name="email" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required="required">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword2">Repeat Password</label>
    <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Repeat Password" name="password2" required="required">
  </div>
    
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<script>
    function registrationComplete() {
        redirectContentDiv("/user/login");
    }
</script>