@extends('site.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/print-screen.css') }}" />
    <style>
        .fund-row:hover {
            background-color: #F8FBFF;
        }
    </style>
@endsection
@section('content')
    <main>
        <section class="dashboard">
            <div class="dashboard__pattern"></div>
            <div class="container">
                <div class="head__container" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="row">
                        <div class="col-md-6">
                            <h3><span class="bold">{{ __('Hello,') }} </span>{{ auth()->user()->name }}</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    @if (\Illuminate\Support\Facades\Auth::check() && auth()->user()->level == 0 && auth()->user()->otp_verified == 1)
                                        <a href="{{ route('upgrade-account.index') }}"
                                            class="landing__btn btn">{{ __('Invest as professional') }}<i
                                                class="fa fa-arrow-up landing__icon icon"></i></a>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('fundbox.index') }}" class="landing__btn btn">{{ __('Invest Now') }}<i
                                            class="fa fa-arrow-up landing__icon icon"></i></a>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12 col-xl-6">
                                    @auth
                                        @if (!auth()->user()->is_upgraded)
                                            <p class="landing__body" style="font-weight: 600;">
                                               {{__('Upgarde Your Account to Show Funds Before Start Of Funds Distribution')}}
                                            </p>
                                            {{-- <a href="#" class="landing__btn btn">
                                            {{ __('ترقية الحساب هنا') }}
                                            <i class="fa-regular fa-crown landing__icon icon"></i>
                                        </a> --}}
                                            <a href="#" class="upgrade__btn btn landing__btn btn" id="upgrade__btn"
                                                data-bs-target="#upgrade_pro_account">{{ __('Upgrade Account') }}<i
                                                    class="fa-regular fa-crown landing__icon icon"></i></a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboard__content" data-aos="zoom-in-up" data-aos-duration="1000">
                    <h1 class="landing__title">{{ __('Dashboard') }}</h1>
                    <div class="overview">
                        <div class="box">
                            <p class="title">{{ __('Wallet Balance') }}</p>
                            <p class="price">{{ auth()->user()->wallet }}</p>
                        </div>
                        <div class="box">
                            <p class="title">{{ __('Total Investments') }}</p>
                            <p class="price">{{ $invest }}</p>
                        </div>
                        <div class="box">
                            <p class="title">{{ __('Returns') }}</p>
                            <p class="price">
                                {{ $profit }}
                            </p>
                        </div>
                    </div>
                    <h2 class="landing__subtitle">{{ __('Portfolio performance') }}</h2>
                    @if ($user->buyed_fundboxes->isEmpty())
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title text-center">{{ __('No Funds invested in Yet') }}</h5>
                            </div>
                        </div>
                    @else
                        <div class="card mb-5">
                            <div class="accordion dashboard-boxes" id="dashboard-boxes">
                                <div class="row">
                                    @foreach ($user->buyed_fundboxes->unique() as $box)
                                        <div class="col-lg-6 col-md-12 mb-4">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed gap-3" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                                        aria-controls="collapseOne">
                                                        <div class="box-image">
                                                            <img src="{{ $box->getFirstMediaUrl('fundbox_main') }}"
                                                                alt="{{ $box->name }}" />
                                                        </div>
                                                        <div class="box-header">
                                                            <h3 class="box-name">
                                                                {{ __('Fund') }} {{ $box->name }}
                                                            </h3>

                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#dashboard-boxes">
                                                    <div class="accordion-body">
                                                        <div id="owl-investigates"
                                                            class="owl-carousel owl-theme owl-investigates">
                                                            @foreach ($box->pivot->where('user_id2', auth()->id())->get() as $transaction)
                                                                <div class="item">
                                                                    <div class="row">

                                                                        <div class="col-sm-7 col-xs-12">
                                                                            <div
                                                                                class="d-flex align-items-center flex-row mb-3">
                                                                                <div class="info-icon">
                                                                                    <img src="{{ asset('assets/dashboard-icons/box-num.svg') }}"
                                                                                        alt="" />
                                                                                </div>
                                                                                <div class="box-info">
                                                                                    <span> {{ __('Amount') }} : </span>

                                                                                    <span>
                                                                                        {{ str($transaction->units_num * $box->unit_price)->append(__('SAR')) }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center flex-row mb-3">
                                                                                <div class="info-icon">
                                                                                    <img src="{{ asset('assets/dashboard-icons/units-num.svg') }}"
                                                                                        alt="" />
                                                                                </div>
                                                                                <div class="box-info">
                                                                                    <span> {{ __('Unit Number') }} :
                                                                                    </span>
                                                                                    <span> {{ $transaction->units_num }}
                                                                                        {{ __('Unit') }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center flex-row mb-3">
                                                                                <div class="info-icon">
                                                                                    <img src="{{ asset('assets/dashboard-icons/location.svg') }}"
                                                                                        alt="" />
                                                                                </div>
                                                                                <div class="box-info">
                                                                                    <span> {{ $box->address }} </span>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center flex-row mb-3">
                                                                                <div class="info-icon">
                                                                                    <img src="{{ asset('assets/dashboard-icons/box-period.svg') }}"
                                                                                        alt="" />
                                                                                </div>
                                                                                <div class="box-info">
                                                                                    <span>
                                                                                        {{ __('Expected Fund Duration') }}
                                                                                        : </span>
                                                                                    <span> {{ $box->duration }}
                                                                                        {{ __('month') }} </span>
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                class="d-flex align-items-center flex-row mb-3">
                                                                                <div class="info-icon d-flex flex-center rounded-circle"
                                                                                    style="background:#4a7eff">
                                                                                    <span><i
                                                                                            class="fa fa-arrow-up landing__icon icon"></i></span>
                                                                                </div>
                                                                                <div class="box-info">
                                                                                    <span> {{ __('Subscription Form') }} :
                                                                                    </span>
                                                                                    <a href="#"
                                                                                        class="button modal-button link-info"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#subscriptionFormModal"
                                                                                        data-related-transaction="{{ $transaction }}"
                                                                                        data-related-fund="{{ $box }}"
                                                                                        data-related-fund-translations="{{ json_encode($box->translations) }}"
                                                                                        data-related-user="{{ $user }}"
                                                                                        data-related-fund-manager="{{ $box?->director?->name ?? 'Admin' }}">
                                                                                        {{ __('Link') }}
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-5 col-xs-12">
                                                                            <div
                                                                                class="d-flex align-items-center flex-column">
                                                                                <div class="box-info">
                                                                                    <span>
                                                                                        {{ $box->finished_and_moneygived == 0 ? __('Expected rate of return on investment') : __('Final rate of return on investment') }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="invest">
                                                                                    <div class="invest-chart"
                                                                                        data-series="{{ $box->finished_and_moneygived == 0 ? $box->final__benefits : $box->profit_ratio }}">
                                                                                    </div>
                                                                                    <span
                                                                                        class="chart-percent">{{ $box->finished_and_moneygived == 0 ? $box->final__benefits : $box->profit_ratio }}%</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="bottom__container">
                        <div class="calc" data-aos="fade-left" data-aos-duration="1000">
                            <h2 class="landing__subtitle">{{ __('How do you calculate your investment?') }}</h2>
                            <div class="how">
                                <div class="how__container">
                                    <div class="boxes__container flex-center" data-aos="fade-up"
                                        data-aos-duration="1000">
                                        <div class="box flex-center">
                                            <div class="icon-arr">
                                                <div class="box__icon flex-center">
                                                    <span class="box__num">01</span>
                                                </div>
                                            </div>
                                            <div class="box__content">
                                                <p class="box__body">
                                                    {{ __('Let\'s say you will invest 10,000 SAR in a real estate fund that offers a return of 25% after 18 months.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="how__arrow">
                                            <img src="{{ asset('assets/shapes/arrow-md.svg') }}" alt="Arrow" />
                                        </div>
                                        <div class="box flex-center">
                                            <div class="box__icon flex-center">
                                                <span class="box__num">02</span>
                                            </div>
                                            <div class="box__content flex-center">
                                                <p class="box__body">
                                                    {{ __('In this case, and at the end of the investment period, you will get your capital of 10,000 and a return of approximately 2,500 Saudi riyals.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="how__arrow">
                                            <img src="{{ asset('assets/shapes/arrow-md.svg') }}" alt="Arrow" />
                                        </div>

                                        <div class="box flex-center">
                                            <div class="box__icon flex-center">
                                                <span class="box__num">03</span>
                                            </div>
                                            <div class="box__content flex-center">
                                                <p class="box__body">
                                                    {{ __('The higher the value of your investment, the higher the return you will get.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chart" data-aos="fade-left" data-aos-duration="1000">
                            <h2 class="landing__subtitle">{{ __('Chart') }}</h2>
                            @if ($user->buyed_fundboxes->isEmpty())
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">{{ __('No Funds invested in Yet') }}</h5>
                                    </div>
                                </div>
                            @else
                                <div id="chart"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>


             {{-- Modal --}}
    <div class="modal fade" id="upgrade_pro_account">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-5" id="upgrade_pro_account_text"
                    data-swal-msg="{{ __('Upgrade Account') }}"
                    data-swal-image="{{ asset('assets/modal-icons/sure.svg') }}"
                    data-swal-confirm-route="{{ route('upgrade-account-pro') }}"
                    data-swal-confirm-token="{{ csrf_token() }}"
                    data-swal-success-icon="{{ asset('assets/modal-icons/success.svg') }}"
                    data-swal-error-icon="{{ asset('assets/modal-icons/error.svg') }}"
                    {{-- data-swal-failed-icon="{{ asset('assets/modal-icons/error.svg') }}" --}}
                    data-swal-failed-msg="{{__('Process Canceled')}}"
                    data-swal-success-msg="{{ __('Account Upgrade Was Successful') }}">
                </div>
            </div>
        </div>
    </div>
        </section>
    </main>

    @include('site.subscriptionFromModal')
@endsection
@push('scripts')
    <script src="https://rawgit.com/OwlCarousel2/OwlCarousel2/develop/dist/owl.carousel.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script src="js/print-screen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        var labels = {!! $funds_name !!};
        var series = {!! $funds_series !!};

        // ApexCharts
        var options = {
            // title:{
            //   text:"{{ __('Chart') }}",
            //   align: 'left',
            //   style: {
            //     fontSize:  '14px',
            //     fontWeight:  'bold',
            //     fontFamily:  undefined,
            //     color:  '#263238'
            //   },
            // },
            series: series,
            labels: labels,
            chart: {
                width: 500,
                type: "donut",
            },
            dataLabels: {
                enabled: false,
            },
            responsive: [{
                breakpoint: 992,
                options: {
                    chart: {
                        width: 400,
                    },
                    legend: {
                        show: false,
                    },
                },
            }, ],
            legend: {
                position: "right",
                offsetY: 0,
                height: 230,
            },
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Function to handle button click and display related data
        $('.modal-button').click(function() {
            var relatedFund = $(this).data('related-fund');
            var relatedTransaction = $(this).data('related-transaction');
            var relatedFundTranslations = $(this).data('related-fund-translations');

            var relatedFundManager = $(this).data('related-fund-manager');
            var relatedUser = {
                name: {
                    ar: "{!! $user->getTranslation('name', 'ar') !!}",
                    en: "{!! $user->getTranslation('name', 'en') !!}"
                },
                national_id: "{!! $user->national_id !!}",
                nationality: {
                    ar: "{!! $user?->nationality?->getTranslation('name', 'ar')
                        ? $user?->nationality?->getTranslation('name', 'ar')
                        : 'سعودي' !!}",
                    en: "{!! $user?->nationality?->getTranslation('name', 'en')
                        ? $user?->nationality?->getTranslation('name', 'en')
                        : 'Saudi' !!}",
                },
            }
            $('#inv_name_ar').text(relatedUser.name.ar);
            $('#inv_name_en').text(relatedUser.name.en);
            $('#inv_id').text(relatedUser.national_id);
            $('#inv_nationality_ar').text(relatedUser.nationality.ar)
            $('#inv_nationality_en').text(relatedUser.nationality.en)

            $('#fund_name_ar').text(relatedFundTranslations.name.ar);
            $('#fund_name_en').text(relatedFundTranslations.name.en);
            $('#fund_manager').text(relatedFundManager);
            $('#fund_units_num').text(relatedTransaction.units_num);

            $('#transaction_amount').text(relatedTransaction.units_num * relatedFund.unit_price);

            const timestamp = relatedFund.start_date; // Example Unix timestamp
            const formattedDate = timestamp.substring(0, 10);
            $('#fund_created_at').text(formattedDate);
        });
    </script>
    <script src="{{ asset('js/upgrade.js') }}"></script>

@endpush
