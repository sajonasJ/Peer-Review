<div class="top-header d-flex justify-content-end align-items-center">
    <div class="top-header-links d-flex justify-content-end align-items-center">
        <a href="#" class="top-header-link">myGriffith</a>
        <span class="divider">|</span>
        <a href="#" class="top-header-link">Staff portal</a>
        <span class="divider">|</span>
        <a href="#" class="top-header-link">Contact us</a>
        <span class="divider">|</span>
        <a href="#" class="top-header-link my-0" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
