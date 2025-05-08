@extends('layouts.app')

@section('css')
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
    left: -32px;
    z-index: 1055;
    padding: 28px 10px;
    border-radius: 0px;
}

.gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.gallery img {
    max-width: 200px;
    height: auto;
    cursor: pointer;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: transform 0.2s ease;
}

.gallery img:hover {
    transform: scale(1.03);
}

.gallery .position-relative {
    display: inline-block;
    position: relative;
}

.delete-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: rgba(255, 0, 0, 0.8);
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    line-height: 20px;
    text-align: center;
}

.delete-btn:hover {
    background-color: red;
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
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light"> /</span> Gallery</h4>

                            <div class="row mt-md-2">
                                <!-- Gallery -->
                                <div class="gallery" id="gallery">
                                    @php
                                        $images = json_decode($gallery->file, true);
                                    @endphp
                                    @foreach ($images as $index => $image)
                                        <div class="position-relative" id="image-wrapper-{{ $index }}">
                                            <img src="{{ asset($image) }}" alt="Gallery Image {{ $index + 1 }}" onclick="openModal({{ $index }})">
                                            <button class="delete-btn" onclick="deleteImage('{{ $image }}', {{ $index }})">&times;</button>
                                        </div>
                                    @endforeach
                                </div>


                                <!-- Modal -->
                                <div class="modale" id="modal">
                                    <span class="close-btn" onclick="closeModal()">&times;</span>
                                    <img id="modalImg" src="" alt="">
                                    <div class="modale-buttons">
                                        <button class="bg-primary text-white" onclick="prevImage()">Back</button>
                                        <button class="bg-primary text-white" onclick="nextImage()">Next</button>
                                    </div>
                                </div>
                                <!-- End Gallery -->
                            </div>
                        </div>

                        <script>
                            let images = @json($images);
                            let currentIndex = 0;

                            function openModal(index) {
                                currentIndex = index;
                                document.getElementById("modalImg").src = "{{ asset('') }}" + images[index];
                                document.getElementById("modal").style.display = "block";
                            }

                            function closeModal() {
                                document.getElementById("modal").style.display = "none";
                            }

                            function prevImage() {
                                currentIndex = (currentIndex - 1 + images.length) % images.length;
                                document.getElementById("modalImg").src = "{{ asset('') }}" + images[currentIndex];
                            }

                            function nextImage() {
                                currentIndex = (currentIndex + 1) % images.length;
                                document.getElementById("modalImg").src = "{{ asset('') }}" + images[currentIndex];
                            }
                        </script>

                <!-- Footer -->
                <x-footer />
                <!-- / Footer -->
            </div>
        </div>
    </div>
</div>

<!-- Add Project Task -->
<div class="offcanvas offcanvas-end w-45" data-bs-backdrop="static" tabindex="-1" id="thoughts_offcanvas" aria-labelledby="staticBackdropLabel">
    <div class="offcanvas-header bg-primary p-3">
        <span class="d-flex justify-content-between align-items-center gap-2">
            <i class="ti ti-file-plus fs-2 text-white"></i>
            <span id="offcanvas-title-container">
                <h5 class="offcanvas-title text-white" id="staticBackdropLabel"> Add Thought</h5>
                <span class="text-white slogan">Create New Thought</span>
            </span>
        </span>
        <button type="button" class="btn btn-danger offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa fa-close"></i> </button>
    </div>
    <div class="offcanvas-body">
        <div class="row">
            <div class="col-sm-12">
                <x-thoughts-form action="{{ route('thoughts.store') }}" />
            </div>
        </div>
    </div>
</div>

@stop


@push('js')
<script>
   function deleteImage(imagePath, index) {
        if (!confirm('Are you sure you want to delete this image?')) return;

        fetch("{{ route('gallery.image.delete', $gallery->id) }}", {
            method: "DELETE",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ image: imagePath })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.getElementById(`image-wrapper-${index}`);
                if (element) element.remove();
            } else {
                alert("Failed to delete image.");
            }
        })
        .catch(error => {
            console.error("Error deleting image:", error);
            alert("Error deleting image.");
        });
    }


    function openModal(index) {
    document.getElementById('modal').style.display = 'flex';
    document.getElementById('modalImg').src = imageList[index]; // assume imageList is your image array
    currentImageIndex = index;
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}



const imageList = [
        @foreach ($images as $image)
            "{{ asset($image) }}",
        @endforeach
    ];

    let currentImageIndex = 0;

    function openModal(index) {
        currentImageIndex = index;
        const modal = document.getElementById('modal');
        const modalImg = document.getElementById('modalImg');

        modalImg.src = imageList[index];
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }

    function prevImage() {
        if (currentImageIndex > 0) {
            openModal(currentImageIndex - 1);
        }
    }

    function nextImage() {
        if (currentImageIndex < imageList.length - 1) {
            openModal(currentImageIndex + 1);
        }
    }
    </script>

@endpush
