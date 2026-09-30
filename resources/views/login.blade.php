<x-login-registration>
<p>dont have a login? <a href="/register">register</a> 
<form method="POST" action="{{ route('login.attempt') }}">
   @csrf
   <x-form-errors />
   <input type="email" name="email" placeholder="email" />
   <input type="password" name="password" placeholder="password" />
   <button type="sumit">sumit</button>
</form>
</x-login-registration>