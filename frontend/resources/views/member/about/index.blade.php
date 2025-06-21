@extends('components.comp_two.layout')

@section('title')
    Member
@endsection

@section('content')
    <section class="hero-wrap hero-wrap-2 js-fullheight"
        style="background-image: url('../memberast/images/member_about.png');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-end justify-content-start">
                <div class="col-md-9 ftco-animate pb-5">
                    <h1 class="mb-3 bread">Tentang Kami</h1>
                    <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('member.index') }}">Home <i
                                    class="ion-ios-arrow-forward"></i></a></span> <span>Tentang Kami <i
                                class="ion-ios-arrow-forward"></i></span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section d-md-flex bg-light">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-md-7">
                    <h2>- Semua Event Hebat Dimulai dari Credivent -</h2>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-counter img" id="section-counter">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-6 d-flex">
                    <div class="img d-flex align-self-stretch" style="background-image:url(../memberast/images/about.jpg);">
                    </div>
                </div>
                <div class="col-md-6 pl-md-5 py-5">
                    <div class="row justify-content-start pb-3">
                        <div class="col-md-12 heading-section ftco-animate">
                            <h2 class="mb-4"><span>Tentang</span> Credivent</h2>
                            <p>Credivent adalah sebuah platform berbasis teknologi yang dirancang khusus untuk 
                                mempermudah pengelolaan acara-acara di lingkungan kampus. Dengan Credivent, seluruh 
                                proses mulai dari perencanaan dan organisasi acara, hingga pengelolaan partisipasi 
                                peserta, dapat dilakukan dengan cara yang lebih efisien dan praktis. Semua fitur yang 
                                dibutuhkan untuk menyelenggarakan acara, baik itu seminar, workshop, ataupun kegiatan 
                                lainnya, tersedia dalam satu platform yang terintegrasi dan mudah digunakan. Dengan 
                                demikian, seluruh aspek pengelolaan acara menjadi lebih terpusat, meminimalisir 
                                kerumitan, dan memberikan kemudahan bagi penyelenggara serta peserta.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-guest"></span>
                                    </div>
                                    <strong class="number" data-number="3000">0</strong>
                                    <span>Peserta</span>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-handshake"></span>
                                    </div>
                                    <strong class="number" data-number="100">0</strong>
                                    <span>Sponsor</span>
                                </div>
                            </div>
                        </div> --}}
                        {{-- <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-chair"></span>
                                    </div>
                                    <strong class="number" data-number="2500">0</strong>
                                    <span>Total Seats</span>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-6 justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center py-4 bg-light mb-4">
                                <div class="text">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="flaticon-idea"></span>
                                    </div>
                                    <strong class="number" data-number="250">0</strong>
                                    <span>Seminar</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section testimony-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading">Keunggulan</span>
                    <h2 class="mb-4"><span>Website</span> Credivent</h2>
                </div>
            </div>
            <div class="row ftco-animate">
                <div class="col-md-12">
                    <div class="carousel-testimony owl-carousel ftco-owl">
                        <div class="item">
                            <div class="testimony-wrap text-center py-4 pb-5">
                                <div class="user-img mb-4" style="background-image: url(../memberast/images/member_about_organize.png)">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="icon-quote-left"></i>
                                    </span>
                                </div>
                                <div class="text">
                                    <p class="mb-4"><strong>Terorganisir & Profesional</strong><br>
                                        Setiap event dirancang dengan perencanaan matang dan eksekusi yang tepat waktu.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap text-center py-4 pb-5">
                                <div class="user-img mb-4" style="background-image: url(../memberast/images/member_about_creative.png)">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="icon-quote-left"></i>
                                    </span>
                                </div>
                                <div class="text">
                                    <p class="mb-4"><strong>Kreatif & Inovatif</strong><br>
                                        Kami hadirkan konsep acara yang segar, menarik, dan sesuai karakter mahasiswa.</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="testimony-wrap text-center py-4 pb-5">
                                <div class="user-img mb-4" style="background-image: url(../memberast/images/member_about_collaborative.png)">
                                    <span class="quote d-flex align-items-center justify-content-center">
                                        <i class="icon-quote-left"></i>
                                    </span>
                                </div>
                                <div class="text">
                                    <p class="mb-4"><strong>Komunikatif & Kolaboratif</strong><br>
                                        Kami menjaga komunikasi yang terbuka dan aktif untuk menciptakan 
                                        kerja sama yang efektif.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
