<form action="{{ url('/post/access') }}" method="post">
	<input type="text" name="username" placeholder="username">
	<input type="password" name="password" placeholder="password">
	<input type="submit" name="submit" value="login" />
</form>