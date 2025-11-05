<x-base.navigation/>
<form action="{{route('authorize.check')}}" method="POST">
    @csrf
    <input type="text" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Авторизоваться</button>
</form>

@if($errors->any())
@dd($errors)
@endif