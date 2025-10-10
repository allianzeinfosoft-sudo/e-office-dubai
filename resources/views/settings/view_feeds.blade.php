@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/ui-carousel.css') }}" />
<style>
.w-35 {
    width: 35% !important;
}
.w-45 {
    width: 45% !important;
}
.offcanvas-close{
    position: absolute;
    top: 0px;
    left: -32px;  /* Moves the button outside the offcanvas */
    z-index: 1055; /* Ensures it stays on top */
    padding: 28px 10px;
    border-radius: 0px;
}
</style>
@stop


@section('content')
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container {{ $background_class ?? 'bg-eoffice' }}">
        <!-- Menu -->
        <x-menu />

        <div class="layout-page">
            <!-- Navbar -->
            <x-header />

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">

              <h4 class="fw-bold py-3">
                <span class="text-white fw-bold">Feeds</span>
              </h4>

              <div class="row overflow-hidden">
                <div class="col-12">
                  <ul class="timeline timeline-center mt-5">



                    <!---Announcment-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-custom timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-volume-up"></i>
                      </span>
                      <div class="timeline-event card-sm card p-0" data-aos="fade-right">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h5 class="text-white my-1">Announcement</h5>
                          <div class="meta my-1">
                            <span class="badge wrd-br bg-label-warning"> Salary slip for the month of March has been uploaded.</span>
                          </div>
                        </div>
                        <div class="card-body ">
                          <p class="mb-2">
                          Hi all,<br>
                          Salary slip for the month of March has been uploaded.
                          </p>
                          <p class="text-fade">
                          <b>Thank You</b>
                          <br>
                          Sujatha P<br>
                          </p>
                          <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <button type="button" class="btn btn-primary w-100">Read More</button>
                            </div>
                            <div>
                                <span class="badge bg-dark">02-January</span>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">01-January</div>
                      </div>
                    </li> --}}
                    <!---Announcment-->



                    <!---Poll-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-custom timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-signal"></i>
                      </span>
                      <div class="timeline-event  card-sm card p-0" data-aos="fade-left">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h5 class="text-white my-1">Poll Now</h5>
                          <div class="meta my-1">
                            <span class="badge wrd-br bg-label-warning"> Test heading</span>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="list-group mb-3" role="tablist">
                            <a class="border list-group-item p-3 badge bg-white list-group-item-action mb-2" id="list-home-list" data-bs-toggle="list" href="#list-home" aria-selected="false" role="tab" tabindex="-1">Home</a>
                            <a class="border list-group-item p-3 badge bg-white list-group-item-action mb-2" id="list-profile-list" data-bs-toggle="list" href="#list-profile" aria-selected="false" role="tab" tabindex="-1">Profile</a>
                            <a class="border list-group-item p-3 badge bg-white list-group-item-action mb-2" id="list-messages-list" data-bs-toggle="list" href="#list-messages" aria-selected="false" role="tab" tabindex="-1">Messages</a>
                            <a class="border list-group-item p-3 badge bg-white list-group-item-action mb-2" id="list-settings-list" data-bs-toggle="list" href="#list-settings" aria-selected="true" role="tab">Settings</a>
                          </div>
                          <p class="">Select your option and click submit</p>
                          <div class="d-flex justify-content-between align-items-center mt-2 flex-wrap">
                            <div>
                                <button type="button" class="btn btn-primary ">Submit</button>
                            </div>
                            <div>
                                <span class="badge bg-dark">02-January</span>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">02-January</div>
                      </div>
                    </li> --}}
                    <!---poll-->




                    <!---img Announcment-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-volume-up"></i>
                      </span>
                      <div class="timeline-event  card-sm card p-0" data-aos="fade-right">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h5 class="text-white my-1">Announcement</h5>
                          <div class="meta my-1">
                            <span class="badge wrd-br bg-label-warning">sports venue and timing</span>
                          </div>
                        </div>
                        <div class="card-body ">
                          <img class="w-100" src="../../assets/img/uploads/test.jpg" alt="Sport venue and timing announcement">
                          <p class="mt-3 mb-2">
                            Hi all,<br>
                            Please review the venue and timing mentioned above.
                            </p>
                            <p class="text-fade">
                            <b>Thank You</b>
                            <br>
                            Sujatha P<br>
                          </p>
                          <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                              <button type="button" class="btn btn-primary w-100">Read More</button>
                            </div>
                            <div>
                              <span class="badge bg-dark">02-January</span>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">01-January</div>
                      </div>
                    </li> --}}
                    <!---img Announcment-->




                    <!---download Announcment-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-volume-up"></i>
                      </span>
                      <div class="timeline-event  card-sm card p-0" data-aos="fade-right">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h6 class="text-white my-1">Announcement</h6>
                          <div class="meta my-1">
                            <span class="badge wrd-br bg-label-warning">Download Company policy</span>
                          </div>
                        </div>
                        <div class="card-body ">
                            <p class="mb-3">
                            Hi,<br>
                            New policy has been uploaded click on the download button to download the file
                            </p>
                            <button class="btn btn-info waves-effect waves-light mb-3">
                              <i class="fa fa-download me-2"></i>Download
                            </button>
                            <p class="text-fade">
                            <b>Thank You</b>
                            <br>
                            Sujatha P<br>
                            </p>
                          <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <button type="button" class="btn btn-primary w-100">Read More</button>
                            </div>
                            <div>
                                <span class="badge bg-dark">02-January</span>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">02-January</div>
                      </div>
                    </li> --}}
                    <!---download Announcment-->




                    <!---Birthday Announcment-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-birthday-cake"></i>
                      </span>
                      <div class="timeline-event  card-sm card p-0" data-aos="fade-right">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h6 class="text-white my-1">Birthday Today</h6>
                          <div class="meta my-1">
                            <button type="button" class="btn btn-primary w-100">
                              <i class="fa fa-paper-plane me-2"></i>Wishes
                            </button>
                          </div>
                        </div>
                        <div class=" text-center">
                          <div id="swiper-gallery">
                            <div class="swiper gallery-top bday-card" >
                              <canvas class="confetti-canvas" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;">                             </canvas>
                              <div class="swiper-wrapper " >
                                <div class="swiper-slide " >
                                  <div class="card-bday">
                                    <img class="bdy-img mt-5 rounded-circle" src="../../assets/img/avatars/user.jpg" alt="5">
                                  </div>
                                  <p class="bdy-name">Nandu Sreekantan</p>
                                </div>
                                <div class="swiper-slide " >
                                  <div class="card-bday">
                                    <img class="bdy-img mt-5 rounded-circle" src="../../assets/img/avatars/2.png" alt="5">
                                  </div>
                                  <p class="bdy-name">Hrithika K V </p>
                                </div>
                                <div class="swiper-slide " >
                                  <div class="card-bday">
                                    <img class="bdy-img mt-5 rounded-circle" src="../../assets/img/avatars/3.png" alt="5">
                                  </div>
                                  <p class="bdy-name">Anilkumar M T</p>
                                </div>
                                <div class="swiper-slide " >
                                  <div class="card-bday">
                                    <img class="bdy-img mt-5 rounded-circle" src="../../assets/img/avatars/5.png" alt="5">
                                  </div>
                                  <p class="bdy-name">Jerson George </p>
                                </div>
                                <div class="swiper-slide text-center" style="position: relative;">
                                  <div class="card-bday">
                                    <img class="bdy-img mt-5 rounded-circle" src="../../assets/img/avatars/6.png" alt="5">
                                  </div>
                                  <p class="bdy-name">Mrinal Thakur</p>
                                </div>
                              </div>
                              <!-- Add Arrows -->
                              <div class="swiper-button-next swiper-button-white"></div>
                              <div class="swiper-button-prev swiper-button-white"></div>
                            </div>
                            <div class="swiper gallery-thumbs">
                              <div class="swiper-wrapper">
                                <div class="swiper-slide" style="background-image: url(../../assets/img/avatars/user.jpg)">
                                </div>
                                <div class="swiper-slide" style="background-image: url(../../assets/img/avatars/2.png)">
                                </div>
                                <div class="swiper-slide" style="background-image: url(../../assets/img/avatars/3.png)">
                                </div>
                                <div class="swiper-slide" style="background-image: url(../../assets/img/avatars/5.png)">
                                </div>
                                <div class="swiper-slide" style="background-image: url(../../assets/img/avatars/6.png)">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">01-January</div>
                      </div>
                    </li> --}}
                    <!---Birthday Announcment-->



                    <!---appreciation Announcment-->
                    {{-- <li class="timeline-item pb-md-4 pb-5">
                      <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                        <i class="fa fa-star"></i>
                      </span>
                      <div class="timeline-event  card-app card p-0" data-aos="fade-right">
                        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                          <h5 class="text-white my-1">Congratulations</h5>
                        </div>
                        <div class="card-body ">
                          <div class="d-flex flex-wrap align-items-center justify-content-center my-3">
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/default-avatar.png" alt="Avatar" class="mx-auto rounded-circle w-px-75" />
                              <span class="bday-name">nandu sreekantan </span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/4.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Sreenath M Viswanathan	</span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/3.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Jayalakshmi J H</span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/2.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Nenitha Elizabeth John	</span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/5.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Yedu Rajeevan</span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/6.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Harikrishnan R </span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/2.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Anoob V</span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/7.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Ananthakrishnan S                              </span>
                            </div>
                            <div class="d-flex flex-column me-2 mb-2">
                              <img src="../../assets/img/avatars/8.png" alt="Avatar" class="mx-auto border-theme rounded-circle w-px-75" />
                              <span class="bday-name">Muhammed Shafi A	</span>
                            </div>

                          </div>
                          <div class="cng-img text-center">
                            <img src="../../assets/img/backgrounds/cng.png">
                          </div>
                          <p class="mt-3 mb-2">
                            🌟 Kudos to Our Team! 🌟<br>
                            Allianze infosoft is thrilled to share some fantastic feedback from our valued client from 200125_AH, Oman 🎉
                            <br>
                            💬 "I appreciate your effort in ensuring the tags are correctly updated. I have reviewed the upload, and everything meets the required standards. Thank you for your diligence. I hope you can continue with the same level of accuracy and commitment."
                            <br>
                            A big shoutout to Ananthakrishnan, Anoob, Harikrishnan, Jayalakshmi, Vipush, and Yedu Rajeevan for their dedication and precision in delivering top-notch results. 👏 Your commitment to quality and excellence is truly commendable!
                            <br>
                            Let’s keep up the great work! 🚀💯
                          </p>
                          <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap">
                            <div>
                              <button type="button" class="btn btn-primary w-100">Congratulate...</button>
                            </div>
                            <div>
                              <span class="badge bg-dark">02-January</span>
                            </div>
                          </div>
                        </div>
                        <div class="timeline-event-time">01-January</div>
                      </div>
                    </li> --}}






                    <!---img Announcment-->
                  </ul>
                </div>
              </div>


                </div>
                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script src="{{ asset('assets/js/ui-carousel.js') }}"></script>
<script>

    // form validation
    $(function () {

    $.ajax({
        type: "GET",
        url: "{{ route('show.feeds') }}", // Your endpoint
        dataType: "json",
        success: function (response) {

            if (response.data && response.data.length) {

                renderTimeline(response.data);

            } else {
                $(".timeline.timeline-center").html("<li class='timeline-item'>No updates for today.</li>");
            }
        },
        error: function (xhr) {
            console.error("Failed to load background images:", xhr);
            $(".timeline.timeline-center").html("<li class='timeline-item text-danger'>Failed to load timeline data.</li>");
        }
    });



function renderTimeline(data) {

    const container = $(".timeline.timeline-center");
    container.empty();

    data.forEach(item => {
        const htmlMap = {
            announcement: getAnnouncementHtml,
            birthday: getBirthdayHtml,
            appreciation: getAppreciationHtml
        };

        const generateHtml = htmlMap[item.type];
        if (typeof generateHtml === 'function') {
            const html = generateHtml(item);
            container.append(`<li class="timeline-item pb-md-4 pb-5">${html}</li>`);
        }
    });
    console.log("Gallery DOM:", document.querySelector(".gallery-top"));
    // Initialize all Swipers AFTER rendering
    setTimeout(() => initBirthdaySwiper(), 300); // Give DOM a moment


}



function initBirthdaySwiper() {
    const galleryThumbs = new Swiper(".gallery-thumbs", {
        spaceBetween: 10,
        slidesPerView: 4,
        watchSlidesProgress: true,
    });

    new Swiper(".gallery-top", {
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: galleryThumbs,
        },
    });
}

});

function getAnnouncementHtml(item) {

    const message = item.message || [];
    const title = item.title || [];
    const display_date = item.display_start_date || [];
    const created_date = item.create_date || [];
    const picture = item.image ? `/storage/${item.image}` : '';

    return `
    <span class="timeline-custom timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
        <i class="fa fa-volume-up"></i>
    </span>
    <div class="timeline-event card-sm card p-0" data-aos="fade-right">
        <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="text-white my-1">Announcement</h5>
            <div class="meta my-1">
                <span class="badge wrd-br bg-label-warning">${title}</span>
            </div>
        </div>
        <div class="card-body">

            <p class="mt-3 mb-2">${message}</p>
            <div class="mb-3 text-center">
                 ${picture ? `<img class="img-fluid" src="${picture}" alt="${title}" />` : ''}
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div><span class="badge bg-dark">${display_date}</span></div>
            </div>
        </div>
        <div class="timeline-event-time">${display_date}</div></div>`;
}



function getBirthdayHtml(item) {
    const employees = item.employees || [];
    const displayDate = item.display_date || 'N/A';

    const slidesHtml = employees.map(emp => `
        <div class="swiper-slide text-center" style="position: relative;">
            <div class="card-bday">
                <img class="bdy-img mt-5 rounded-circle" src="/storage/${emp.profile_image}" alt="${emp.full_name}">
            </div>
            <p class="bdy-name">${emp.full_name}</p>
        </div>
    `).join('');

    const thumbsHtml = employees.map(emp => `
        <div class="swiper-slide" style="background-image: url('/storage/${emp.profile_image}')"></div>
    `).join('');

    return `
        <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
            <i class="fa fa-birthday-cake"></i>
        </span>
        <div class="timeline-event card-sm card p-0" data-aos="fade-right">
            <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                <h6 class="text-white my-1">Birthday Today</h6>
                <div class="meta my-1">
                    <button type="button" class="btn btn-primary w-100">
                        <i class="fa fa-paper-plane me-2"></i>Wishes
                    </button>
                </div>
            </div>
            <div class="text-center">
                <div id="swiper-gallery">
                    <div class="swiper gallery-top bday-card">
                        <canvas class="confetti-canvas" style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:10;"></canvas>
                        <div class="swiper-wrapper">
                            ${slidesHtml}
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper gallery-thumbs">
                        <div class="swiper-wrapper">
                            ${thumbsHtml}
                        </div>
                    </div>
                </div>
            </div>
            <div class="timeline-event-time">${displayDate}</div>
        </div>`;
}


function getAppreciationHtml(item) {
    const employees = item.employees || [];
    const displayDate = item.display_date || 'N/A';
    const message = item.message || '';
    const image = item.image
        ? `/storage/appreciation_flowers/${item.image}`
        : '/assets/img/backgrounds/cng.png';

    const mailtoList = employees
        .map(emp => emp.email)
        .filter(email => !!email)
        .join(',');

    const mailtoLink = `mailto:${mailtoList}?subject=${encodeURIComponent('Congratulations!')}&body=${encodeURIComponent('Congratulations!')}`;

    const employeeHtml = employees.map(emp => {
        const profileImage = emp.profile_image && emp.profile_image !== '/assets/img/avatars/default.png'
            ? `/storage/${emp.profile_image}`
            : '/assets/img/avatars/default.png';

        return `
            <div class="d-flex flex-column me-2 mb-2 text-center">
                <img src="${profileImage}" alt="${emp.full_name}" class="mx-auto border-theme rounded-circle w-px-75" />
                <span class="bday-name">${emp.full_name}</span>
            </div>

        `;
    }).join('');

    return `
        <span class="timeline-indicator timeline-custom timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
            <i class="fa fa-star"></i>
        </span>
        <div class="timeline-event card-app card p-0" data-aos="fade-right">
            <div class="card-header p-3 bg-black mb-4 d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="text-white my-1">Congratulations</h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-center my-3">
                    ${employeeHtml}
                </div>
                <div class="cng-img text-center">
                    <img class="w-40" src="../../assets/img/backgrounds/cng.png">
                    <img class="w-25" src="${image}" alt="Appreciation Background">
                </div>
                <p class="mt-3 mb-2 text-center">
                    ${message}
                </p>
                <div class="d-flex justify-content-between align-items-center mt-5 flex-wrap">
                    <div>
                        <a class="text-primary" href="${mailtoLink}">
                            <button type="button" class="btn btn-primary w-100">Congratulate...</button>
                        </a>
                    </div>
                    <div>
                        <span class="badge bg-dark">${displayDate}</span>
                    </div>
                </div>
            </div>
            <div class="timeline-event-time">${displayDate}</div>
        </div>
    `;
}





function runConfettiEffect(selector = ".card-app") {
    const container = document.querySelector(selector);
    if (!container) return;

    const confettiCount = 40;
    for (let i = 0; i < confettiCount; i++) {
        const confetti = document.createElement("div");
        confetti.classList.add("confetti", Math.random() > 0.5 ? "red" : "gold");
        confetti.style.left = `${Math.random() * 100}%`;
        confetti.style.top = `${-20 - Math.random() * 100}px`;
        confetti.style.animationDuration = `${3 + Math.random() * 2}s`;
        confetti.style.animationDelay = `${Math.random() * 3}s`;
        container.appendChild(confetti);
    }
}

function renderTimeline(data) {
    const container = $(".timeline.timeline-center");
    container.empty();

    data.forEach(item => {
        const htmlMap = {
            announcement: getAnnouncementHtml,
            birthday: getBirthdayHtml,
            appreciation: getAppreciationHtml
        };

        const generateHtml = htmlMap[item.type];
        if (typeof generateHtml === 'function') {
            const html = generateHtml(item);
            container.append(`<li class="timeline-item pb-md-4 pb-5">${html}</li>`);
        }
    });

    initConfettiForGalleryTop();
}

function initConfettiForGalleryTop() {
    const galleryTop = document.querySelector('.gallery-top');
    if (!galleryTop) return;

    const canvas = galleryTop.querySelector('.confetti-canvas');
    if (!canvas) return;

    const myConfetti = confetti.create(canvas, {
        resize: true,
        useWorker: true
    });

    setInterval(() => {
        myConfetti({
            particleCount: 15,
            spread: 80,
            startVelocity: 30,
            gravity: 0.5,
            ticks: 200,
            origin: {
                x: Math.random(),
                y: Math.random()
            },
            colors: ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff', '#ffffff'],
            shapes: ['square', 'circle']
        });
    }, 250);
}



</script>
@endpush
