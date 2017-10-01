@if (session('message'))
    <div class="alert {{ session('message')['class'] }}">{{ session('message')['content'] }}</div>
@endif
