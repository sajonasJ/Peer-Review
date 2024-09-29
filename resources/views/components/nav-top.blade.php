<div class="top-header">
    <div class="top-header-links">
        <a href="#" class="top-header-link">myGriffith</a>
        <span class="divider">|</span>
        <a href="#" class="top-header-link">Staff portal</a>
        <span class="divider">|</span>
        <a href="#" class="top-header-link">Contact us</a>
        <span class="divider">|</span>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <a href="#" class="top-header-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </form>
    </div>
</div>
