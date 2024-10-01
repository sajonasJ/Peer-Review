<main>
    <div id="courseBanner">
        <div class="course-banner-text">
            @isset($course)
                <h3 class="course-banner-code">{{ $course->name }}</h3>
                <h3 class="course-banner-title">{{ $course->course_code }}</h3>
            @else
                <h3 class="course-banner-code">Web App Development Assignment #2</h3>
                <p class="course-banner-title">2703 ICT</p>
            @endisset
        </div>
    </div>
</main>
