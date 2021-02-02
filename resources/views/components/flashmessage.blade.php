@if (session()->has('success'))
<div class="ui icon positive message">
	<i class="checkmark icon"></i>
  <div class="content">
    <div class="header">
      Success!
    </div>
    <p>{{ session('success') }}</p>
  </div>
	
</div>
@endif

@if (session()->has('error'))
<div class="ui icon negative message">
	<i class="exclamation icon"></i>
  <div class="content">
    <div class="header">
      Error!
    </div>
    <p>{{ session('error') }}</p>
  </div>
	
</div>
@endif

@if ($errors->any())
<div class="ui icon negative message">
	<i class="exclamation circle icon"></i>
  <div class="content">
    <div class="header">
      Error!
    </div>
    <p>{{ $errors->first() }}</p>
  </div>
	
</div>
@endif