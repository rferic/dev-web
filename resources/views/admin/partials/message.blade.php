@if (session('message') && isset(session('message')['class']) && session('message')['content'])
    <div class="alert {{ session('message')['class'] }}">{{ session('message')['content'] }}</div>
@endif
