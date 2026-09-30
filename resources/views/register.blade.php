<x-login-registration>
<form method="POST" action="{{ route('register.store') }}">
   @csrf
   <x-form-errors />
   <input type="text" name="name" placeholder="name" />
   <input type="email" name="email" placeholder="email" />
   <input type="password" name="password" placeholder="password" />
   <button type="sumit">sumit</button>
</form>
</x-login-registration>