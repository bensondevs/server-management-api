@if(session()->get('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}  
    </div><br/>
@elseif(session()->get('error'))
    <div class="alert alert-danger">
        {{ session()->get('error') }}
    </div><br/>
@endif