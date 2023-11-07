@extends('site.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/funds.css') }}" />
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
    <style>
        .swal-title {
            inline-size: inherit;
            block-size: 3rem;
            margin-bottom: 30% !important;
        }
    </style>
@endsection

@section('content')
    <section class="desc__container">
        <div class="desc__item" data-aos="fade-up" data-aos-duration="1000">
            <div class="slider__desc" data-aos="fade-right" data-aos-duration="1000">
                <div class="slider-container">
                    <div class="slide-number" id="slide-number"></div>
                    @foreach ($fundbox->images as $image)
                        <img src="{{ $image->getUrl() }}" />
                    @endforeach

                </div>
                <div class="slider-controls">
                    <span class="prev" id="prev"><img src="{{ asset('assets/main-page/left_arrow.png') }}"
                            alt="" /></span>
                    <span class="indicators" id="indicators"> </span>
                    <span class="next" id="next"><img src="{{ asset('assets/main-page/right_arrow.png') }}"
                            alt="" /></span>
                </div>
            </div>
        </div>
        <div class="desc__item">
            <div class="desc" data-aos="fade-right" data-aos-duration="1000">
                <div class="desc__header">
                    <h3></h3>
                    <h4 type="button" data-bs-toggle="modal" data-bs-target="#shareModal">
                        {{ __('Share') }} <i class="fa fa-arrow-up shareIcon"></i>
                    </h4>
                </div>
                <h2>
                    @if (app()->getLocale() == 'en')
                        {{ $fundbox->name }}<br />
                        {{ __('Fund') }}
                    @else
                        {{ __('Fund') }}
                        {{ $fundbox->name }}<br />
                    @endif
                </h2>
            </div>
            <div class="desc__offers">
                <div class="card desc__offer" data-aos="fade-left" data-aos-duration="1000">
                    <div class="upper__content">
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/target.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Rehan Coverage') }}</p>
                                <h3>{{ number_format($fundbox->goal, 0) }} {{ __('SAR') }}</h3>
                            </div>
                        </div>
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/iconexpress.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Fund Manager') }}</p>
                                <h3>{{ $fundbox->director->name ?? null }}</h3>
                            </div>
                        </div>
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/uniprice.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Unit Price') }}</p>
                                <h3>{{ $fundbox->unit_price }}{{ __('SAR') }}</h3>
                            </div>
                        </div>
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/allunits.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Total Units') }}</p>
                                <h3>{{ $fundbox->goal / $fundbox->unit_price }} {{ __('Unit') }}</h3>
                            </div>
                        </div>
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/icontime.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Fund Duration') }}</p>
                                <h3>{{ $fundbox->duration }} {{ __('month') }}</h3>
                            </div>
                        </div>
                        <div class="box">
                            <img src="{{ asset('assets/fund-imgs/iconunit.svg') }}" alt="" />
                            <div>
                                <p>{{ __('Minimum investment') }}</p>
                                <h3>{{ $fundbox->min_units * $fundbox->unit_price }}{{ __('SAR') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="bottom__content">
                        <div class="coverd">
                            @if (app()->getLocale() == 'en')
                                <h3>{{ $fundbox->soldUnitsFromBox() }} {{ __('SAR') }} {{ __('covered') }}</h3>
                            @else
                                <h3>{{ __('covered') }} {{ $fundbox->soldUnitsFromBox() }} {{ __('SAR') }}</h3>
                            @endif
                            <h2>{{ round($fundbox->soldUnitsFromBox() / $fundbox->goal, 3) * 100 }}%</h2>
                        </div>
                        <div class="progress" style="width: 100%; margin-top: -5px">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100"
                                aria-valuemin="0" aria-valuemax="100"
                                style="width:  {{ round($fundbox->soldUnitsFromBox() / $fundbox->goal, 3) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="info-container">
        <div class="info-container__item" data-aos="fade-up-left" data-aos-duration="1000">
            <div class="accordion col-lg-11 col-md-12" id="fundInfo">

                @auth
                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#fintech-data" aria-expanded="true" aria-controls="collapseOne">
                                <img src="{{ asset('assets/fund-imgs/fintech-data.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Financial Details') }}</span>
                            </button>
                        </h2>
                        <div id="fintech-data" class="accordion-collapse collapse show" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <div class="box">
                                    <div>
                                        <p>{{ __('Expected rate of return on investment') }}</p>
                                        <h3 class="num">{{ $fundbox->final__benefits }}%</h3>
                                    </div>
                                    <div>
                                        <p>{{ __('Expected annual rate of return') }}</p>
                                        <h3 class="num">{{ $fundbox->yearly_benefits }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#aboutProject" aria-expanded="true" aria-controls="collapseTwo">
                                <img src="{{ asset('assets/fund-imgs/about-project.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('About The Project') }} </span>
                            </button>
                        </h2>
                        <div id="aboutProject" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">

                                <p>
                                    {!! $fundbox->description !!}
                                </p>

                            </div>
                        </div>
                    </div>

                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#attachments" aria-expanded="true" aria-controls="collapseThree">
                                <img src="{{ asset('assets/fund-imgs/attachments.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Attachments') }}</span>
                            </button>
                        </h2>
                        <div id="attachments" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <div class="box">
                                    @foreach ($fundbox->documents as $doc)
                                        <div class="attachment">
                                            <h5 class="attach__title">{{ $doc->name }}</h5>
                                            <a href="{{ $doc->getUrl() }}" download><i class="fa fa-download"></i></a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#team"
                                aria-expanded="true" aria-controls="collapseFour">
                                <img src="{{ asset('assets/fund-imgs/team.svg') }}" alt="" class="accordion-icon" />
                                <span class="container__title">{{ __('Team') }}</span>
                            </button>
                        </h2>
                        <div id="team" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <ul class="team__list">
                                    <div class="row">
                                        @foreach ($fundbox->boxboardmembers as $member)
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <li class="team__item">
                                                    <div class="profile">
                                                        <img src="{{ asset('assets/fund-imgs/team-member.svg') }}"
                                                            alt="" class="member__icon" />
                                                    </div>
                                                    <div class="info">
                                                        <h4 class="name">{{ $member->name }}</h4>
                                                        <h5 class="role">{{ $member->description }}</h5>
                                                    </div>
                                                </li>
                                            </div>
                                        @endforeach
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#location" aria-expanded="true" aria-controls="collapseFive">
                                <img src="{{ asset('assets/fund-imgs/location.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Location') }}</span>
                            </button>
                        </h2>
                        <div id="location" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <p>{{ $fundbox->address }}</p>
                                <div class="mapouter">
                                    <div class="gmap_canvas">
                                        <div id="my_map_add" style="width: 100%; height: 300px"
                                            lat="{{ $fundbox->location?->latitude }}"
                                            lng="{{ $fundbox->location?->longitude }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#investigateUpdates" aria-expanded="true" aria-controls="collapseSix">
                                <img src="{{ asset('assets/fund-imgs/updates.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Investment Updates') }}</span>
                            </button>
                        </h2>
                        <div id="investigateUpdates" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                @if($fundbox->trackers->count() == 0)
                                    <p>
                                        {{ __('Latest Updates Will be Available Soon') }}
                                    </p>
                                @else
                                    <ul class="list-group list-group-flush">
                                        @foreach ($fundbox->trackers as $update)
                                            <li class="list-group-item">
                                                {!! str($update->message)->replace(',','</br>')->markdown() !!}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#investigateRisks" aria-expanded="true" aria-controls="collapseSeven">
                                <img src="{{ asset('assets/fund-imgs/danger.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Investment Risks') }}</span>
                            </button>
                        </h2>
                        <div id="investigateRisks" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <p>{{ __('The Risks are Mentioned in the Terms and Conditions') }}
                                </p>
                            </div>
                        </div>
                    </div>

                @endauth
                @guest
                    <div class="accordion-item sec" data-aos="fade-up-left" data-aos-duration="1000">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#login" aria-expanded="true" aria-controls="collapseSeven">
                                <img src="{{ asset('assets/fund-imgs/danger.svg') }}" alt=""
                                    class="accordion-icon" />
                                <span class="container__title">{{ __('Login') }}</span>
                            </button>
                        </h2>
                        <div id="login" class="accordion-collapse collapse" data-bs-parent="#fundInfo">
                            <div class="accordion-body">
                                <p>
                                    {{ __('We would like to clarify that registration is a prerequisite for viewing the details of the opportunity, in accordance with the requirements of the Capital Market Authority.') }}

                                </p>
                                <div class="d-flex justify-content-center align-items-center flex-column gap-3">
                                    <a href="{{ route('register') }}" class="btn sign-btn">
                                        {{ __('Join Us Now') }}
                                        <i class="fa-regular fa-arrow-up main-nav__icon icon"></i>
                                    </a>
                                    <a href="{{ route('login') }}" class="btn sign-btn">
                                        {{ __('Login') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
        </div>

        @if (auth()->check() && $fundbox->status->value == 2)
            <div class="info-container__item blue-box" data-aos="flip-left" data-aos-duration="1000">
                <h1>{{ __('Investing in a real estate fund') }}</h1>
                <p class="balance-container">
                    {{ __('Balance') }} : <br /><span id="balance" data-balance="{{ auth()->user()->wallet }}"></span>
                    {{ __('SAR') }}

                </p>
                <form class="invest-form" id="invest-form" action="{{ route('fundbox.buy') }}" method="post"
                    data-user-type="{{ auth()->user()->level }}" data-vat="15" data-fees="2"
                    data-otp-request-url="{{ route('fundbox.send-otp') }}"
                    data-otp-validation-url="{{ route('fundbox.confirm-otp') }}"
                    data-upgrade-form-url="{{ route('upgrade-account.index') }}"
                    data-wallet-url="{{ route('wallet.index') }}">
                    @csrf
                    <div class="box">
                        <h4>{{ __('Choose the number of units') }}</h4>
                        <p>{{ __('Unit price: ') }} <span id="price"></span></p>
                        <div class="counter">
                            <button type="button" id="dec-count-btn">
                                <i class="fa fa-minus counter-icon"></i>
                            </button>

                            <input type="number" id="units-input" name="units"
                                data-unit-price="{{ $fundbox->unit_price }}" min="3"
                                data-min-units-can-buy="{{ $fundbox->maxUnitsForNormalUser(auth()->id()) == 0 ? 0 : ($fundbox->availableUnits < $fundbox->min_units ? $fundbox->availableUnits : $fundbox->min_units) }}"
                                data-max-units-can-buy="{{ $fundbox->maxUnitsForNormalUser(auth()->id()) }}"
                                data-max-available="{{ $fundbox->availableUnits }}" max="{{ $fundbox->availableUnits }}"
                                value="{{ $fundbox->id }}" />
                            <input type="hidden" name="fundbox" value="{{ $fundbox->id }}">
                            <button type="button" id="inc-count-btn">
                                <i class="fa fa-plus counter-icon"></i>
                            </button>
                        </div>
                    </div>
                    <h2>
                        {{ __('Total') }} :
                        <span id="units-price">0.0</span>
                        {{ __('SAR') }}
                    </h2>
                    <button class="landing__btn btn form-submit-btn" id="form-submit-btn" type="button">
                        {{ __('Invest In Fund') }}
                        <i class="fa-regular fa-arrow-up landing__icon icon"></i>
                    </button>
                </form>
                @if ($errors->any())
                    <div class="alert alert-danger mt-5">
                        @foreach ($errors->all() as $error)
                            <span>{{ $error }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif(auth()->check() && $fundbox->status->value == 1)
            <div class="info-container__item blue-box login-box" data-aos="flip-left" data-aos-duration="1000">
                <h1>{{ __('Investing in a real estate fund') }}</h1>
                <div class="box">
                    <h2>{{ __('The Fund will open soon') }}</h2>
                </div>
            </div>
        @elseif(auth()->check() && $fundbox->status->value == 3)
            <div class="info-container__item blue-box login-box" data-aos="flip-left" data-aos-duration="1000">

                <h1>{{ __('Investing in a real estate fund') }}</h1>
                <div class="box">
                    <h2>{{ __('The Fund is closed') }}</h2>
                </div>
            </div>


        @endif

        @guest
            <div class="info-container__item blue-box login-box" data-aos="flip-left" data-aos-duration="1000">
                <h1>{{ __('Investing in a real estate fund') }}</h1>
                <div class="box">
                    <h2>{{ __('You Should Login to Invest') }}</h2>
                </div>
                <a class="landing__btn btn login-btn" href="{{ route('login') }}">
                    {{ __('Login') }}
                    <i class="fa-regular fa-arrow-up landing__icon icon"></i>
                </a>
            </div>
        @endguest
        {{-- @endif --}}
    </section>

    <!-- Modal -->
    <div class="modal fade" id="shareModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="modal-title">{{ __('Share Fund') }}</h3>
                    <ul class="socialLinks">
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('fundbox.show', $fundbox->slug) }}"
                                target="_blank" rel="noopener"><img src="{{ asset('assets/icons/facebook.png') }}"
                                    alt="" />
                            </a>
                        </li>
                        <li>
                            <a href="https://api.whatsapp.com/send/?text={{ route('fundbox.show', $fundbox->slug) }}&type=custom_url&app_absent=0"
                                target="_blank">
                                <img src="{{ asset('assets/icons/whatsapp.png') }}" alt="" />
                            </a>
                        </li>
                        {{-- <li>
                            <a href="https://t.me/share/url?url={{ route('fundbox.show', $fundbox->slug) }}"
                                target="_blank"><img src="{{ asset('assets/icons/telegram.png') }}" alt="" />
                            </a>
                        </li> --}}
                        <li>
                            <a href="https://www.linkedin.com/cws/share?url={{ route('fundbox.show', $fundbox->slug) }}"
                                target="_blank"><img src="{{ asset('assets/icons/linkedin.png') }}"
                                    alt="" /></a>
                        </li>
                        <li>
                            <a href="https://twitter.com/share?url={{ route('fundbox.show', $fundbox->slug) }}"
                                target="_blank"><img src="{{ asset('assets/icons/twitter.png') }}" alt="" />
                            </a>
                        </li>
                        {{-- <li><a href="mailto:?subject=I wanted you to see this site&amp;body=Check out this site {{ route('fundbox.show', $fundbox->slug) }}"
                                target="_blank"><img src="{{ asset('assets/icons/gmail.png') }}" alt="" /></a>
                        </li> --}}
                        <li>
                            <a href="https://www.instagram.com/sharer.php?u={{ route('fundbox.show', $fundbox->slug) }}"><img
                                    src="{{ asset('assets/icons/instagram.png') }}" alt="" /></a>
                        </li>
                    </ul>
                    <button class="btn mx-auto mb-3 copyToClipboard"
                        data-copy="{{ route('fundbox.show', $fundbox->slug) }}">{{ __('Copy Link') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ***** Sweet Alerts Templates ***** -->

    <!-- ?! 1- INVEST MODAL !? -->
    <template id="invest-modal">
        <swal-title>
            {{ __('Are you sure to buy this number of units ?') }}
        </swal-title>
        <swal-html>
            <table id="confirm-invest-table" class="confirm-invest-table">
                <tr>
                    <td>{{ __('Number Of Units') }}</td>
                    <td><span id="ci-total-units"></span>{{ __('Unit') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Unit Price') }}</td>
                    <td><span id="ci-unit-price"></span>{{ __('SAR') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Unit Prices') }}</td>
                    <td><span id="ci-units-price"></span>{{ __('SAR') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Subscription Fees') }}</td>
                    <td><span id="ci-subscription-fee"></span>{{ __('SAR') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Value added Tax on the Subscription Fees') }}</td>
                    <td><span id="ci-subscription-fee-vat"></span>{{ __('SAR') }}</td>
                </tr>
                <tr>
                    <td>{{ __('Total') }}</td>
                    <td><span id="ci-total-price"></span>{{ __('SAR') }}</td>
                </tr>
            </table>

            <label for="swal2-checkbox" class="swal2-checkbox d-flex">
                <input type="checkbox" id="swal2-checkbox">
                <span class="swal2-label">{{ __('I Acknowledge that I have read the Terms and Conditions File') }}</span>
            </label>

        </swal-html>
        <swal-button type="confirm" aria-label="fund_form_submit_btn">{{ __('yes') }} </swal-button>
        <swal-button type="cancel">{{ __('لا') }} </swal-button>
        <swal-param name="allowOutsideClick" value="false" />
        <swal-param name="showLoaderOnConfirm" value="true" />
        <swal-function-param name="didOpen" value="updateInvestModalValues" />
        <swal-function-param name="preConfirm" value="sendOTPRequest" />
    </template>

    <!-- ?! 2- OTP MODAL !? -->
    <template id="otp-modal">
        <swal-title>
            {{ __('Enter the 4-digit verification code sent to your mobile') }}
        </swal-title>
        <swal-image src="/assets/modal-icons/otp.png" alt="OTP icon" />
        <swal-html>
            <div dir="ltr" class="otp-inputs">
                <input type="number" id="otpInput1" class="form-control funds-input otp-input" maxlength="1"
                    inputmode="numeric" pattern="[0-9]" aria-label="Enter the first digit of the OTP" min="0"
                    max="9" required />
                <input type="number" id="otpInput2" class="form-control funds-input otp-input" maxlength="1"
                    inputmode="numeric" pattern="[0-9]" aria-label="Enter the second digit of the OTP" min="0"
                    max="9" required />
                <input type="number" id="otpInput3" class="form-control funds-input otp-input" maxlength="1"
                    inputmode="numeric" pattern="[0-9]" aria-label="Enter the third digit of the OTP" min="0"
                    max="9" required />
                <input type="number" id="otpInput4" class="form-control funds-input otp-input" maxlength="1"
                    inputmode="numeric" pattern="[0-9]" aria-label="Enter the fourth digit of the OTP" min="0"
                    max="9" required />
            </div>
        </swal-html>
        <swal-button type="confirm">{{ __('confirmation') }} </swal-button>
        <swal-param name="allowEscapeKey" value="false" />
        <swal-param name="allowOutsideClick" value="false" />
        <swal-param name="showLoaderOnConfirm" value="true" />
        <swal-function-param name="didOpen" value="handleOTPInputs" />
        <swal-function-param name="preConfirm" value="handleOTPSubmission" />
    </template>

    <!-- ?! 3- SUCCESS MODAL !? -->
    <template id="success-modal">
        <swal-title>{{ __('The purchase was completed successfully') }}
            <swal-image src="/assets/modal-icons/success.svg" alt="success icon" />
            <swal-button type="confirm">{{ __('OK') }}</swal-button>
    </template>

    <!-- ?! 4- FAIL MODAL !? -->
    <template id="fail-modal">
        <swal-title>{{ __('Purchase failed') }} </swal-title>
        <swal-image src="/assets/modal-icons/failed.svg" alt="fail icon" />
        <swal-button type="confirm">{{ __('OK') }} </swal-button>
    </template>

    <!-- MIN AMOUNT CAN BUY MODAL -->
    <template id="min-amount-modal">
        <swal-title>{{ __('You cant buy less than') }}
            <span id="ma-min-units-number"></span> {{ __('Unit') }}وحدة
        </swal-title>
        <swal-image src="/assets/modal-icons/min.svg" alt="fail icon" />
        <swal-button type="confirm"> {{ __('OK') }}</swal-button>
        <swal-function-param name="didOpen" value="updateMinAmountModalValues" />
    </template>

    <!-- BALANCE NOT ENOUGHT TO BUY MODAL -->
    <template id="balance-not-enough-modal">
        <swal-title>
            {{ __('Your balance is not enough to buy') }}<span id="bne-units-number"></span> {{ __('Unit') }}
        </swal-title>
        <swal-image src="/assets/modal-icons/banking.svg" alt="Bank icon" />
        <swal-button type="confirm">{{ __('Recharge the Balance') }}</swal-button>
        <swal-function-param name="didOpen" value="updateNotEnoughMoneyModalValues" />
    </template>

    <!-- UPGRADE MODAL -->
    <template id="upgrade-modal">
        <swal-title>
            {{ __('Upgrade your account to complete the investment process') }}
            <swal-image src="/assets/fund-imgs/investment.png" alt="Upgrade icon" />
            <swal-button type="confirm">{{ __('Account promotion') }} </swal-button>
            <swal-button type="cancel">{{ __('Cancel') }} </swal-button>
    </template>

    <!-- ***** Sweet Alerts Templates End ***** -->
@endsection

@push('scripts')
    <script type="text/javascript">


        function my_map_add() {
            lat = document.getElementById("my_map_add").getAttribute("lat");
            lng = document.getElementById("my_map_add").getAttribute("lng");
            var myMapCenter = new google.maps.LatLng(lat, lng);
            var myMapProp = {
                center: myMapCenter,
                zoom: 12,
                scrollwheel: false,
                draggable: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
            };
            var map = new google.maps.Map(
                document.getElementById("my_map_add"),
                myMapProp
            );
            var marker = new google.maps.Marker({
                position: myMapCenter
            });
            marker.setMap(map);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('MAP_KEY') }}&callback=my_map_add"></script>

    <script>
        $('.copyToClipboard').click(function() {
            let link = $(this).attr('data-copy');
            navigator.clipboard.writeText(link);
            sweetAlert.fire({
                icon: 'success',
                title: "{{ __('Copied') }}",
                showConfirmButton: false,
                timer: 1000
            })
        });
    </script>
    <script src="{{ asset('js/slider.js') }}"></script>
    <script src="{{ asset('js/funds.js') }}"></script>

    @if ($errors->any())
        <script>
            swal({
                content: {
                    element: "img",
                    attributes: {
                        src: "{{ asset('assets/modal-icons/failed.svg') }}",
                        className: "swalIcon",
                    },
                },
                title: '<div style="color:#dc3545" >{!! implode('<br/>', $errors->all()) !!} </div>',
                title: '{{ collect($errors->all())->first() }}',
            })
        </script>
    @endif

@endpush
