@extends('site.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/funds.css') }}" />
@endsection

@section('content')
    <!-- Main -->
    <main>
        <!-- Landing Section -->
        <section class="funds-landing">
            <div class="funds__pattern"></div>
            <div class="container">
                <div class="landing-content__container flex-center">
                    <div class="landing__content" data-aos="fade-right" data-aos-duration="1000">
                        <h1 class="landing__title">{{ __('Real Estate Funds') }}</h1>
                        <p class="landing__body">
                            {{ __('All real estate funds offered on the platform are managed by financial companies licensed by the Capital Market Authority.') }}
                        </p>
                        {{-- Add Upgrade Buttons and Functionality here --}}
                        @auth
                            @if (!auth()->user()->is_upgraded)
                                <p class="landing__body" style="font-weight: 600;">
                                    [ النص التوجيهي لترقية الحساب ]
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
                        {{-- End Of Upgrade Button --}}
                        <p class="landing__body" style="font-weight: 600;">
                            {{ __('If you want a fund to invest in rehan') }}
                        </p>
                        <a href="{{ route('realestatedev.index') }}" class="landing__btn btn">
                            {{ __('Real Estates Development') }}
                            <i class="fa-regular fa-arrow-up landing__icon icon"></i>
                        </a>
                    </div>
                    <div class="landing__pic" data-aos="fade-left" data-aos-duration="1000">
                        <img src="{{ asset('assets/design-imgs/funds-page.png') }}" alt="" />
                    </div>
                </div>
            </div>
        </section>
        <!-- Fund Section -->

        <!-- Fund Section -->
        <section class="fund-offers" id="fund-offers">
            <div class="container">
                @forelse($fundboxes as $fund)
                    <div @class([
                        'offer',
                        // 'disable-click' => ,
                        'blocked' => $fund->availableUnits == 0,
                    ]) @if (!$fund->availableUnits != 0)
                @endif >


                <div>
                    <a href="{{ route('fundbox.show', $fund->slug) }}">
                        <div class="fund-box-image" data-aos="fade-left" data-aos-duration="1000">
                            <img src="{{ $fund->getFirstMediaUrl('fundbox_main') }}" alt="" />
                        </div>
                        {{-- <a href="{{ route('fundbox.show', $fund->slug) }}">
                        <img src="{{ $fund->getFirstMediaUrl('fundbox_main') }}" class="fund-img" alt=""
                            data-aos="fade-left" data-aos-duration="1000" />
                    </a> --}}

                        <div class="card" data-aos="fade-left" data-aos-duration="1000">
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/Group 1643target.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Rehan Coverage') }}</p>
                                    <h3>{{ number_format($fund->goal, 0) }} {{ __('SAR') }}</h3>
                                </div>
                            </div>
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/iconexpress.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Financial Express') }}</p>
                                    <h3> {{ $fund->director->name ?? null }}</h3>
                                </div>
                            </div>
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/iconreturn.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Expected return') }}</p>
                                    <h3>{{ $fund->final__benefits }}%</h3>
                                </div>
                            </div>
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/iconlocation.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Location') }}</p>
                                    <h3>{{ $fund->address }}</h3>
                                </div>
                            </div>
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/icontime.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Fund Duration') }}</p>
                                    <h3> {{ $fund->duration }} {{ __('month') }}</h3>
                                </div>
                            </div>
                            <div class="box">
                                <img src="{{ asset('assets/fund-imgs/iconunit.svg') }}" alt="" />
                                <div>
                                    <p>{{ __('Minimum investment') }}</p>
                                    <h3>{{ $fund->min_units * $fund->unit_price }}{{ __('SAR') }} </h3>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="desc" data-aos="fade-right" data-aos-duration="1000">
                    <h4 type="button" data-bs-toggle="modal" data-bs-target="#shareModal--{{ $fund->id }}">
                        {{ __('Share') }} <i class="fa fa-arrow-up shareIcon"></i>
                    </h4>
                    <h2>
                        <a href="{{ route('fundbox.show', $fund->slug) }}" style="color:#383f82">
                            {{ __('Fund') }}
                            <br>
                            {{ $fund->name }}
                        </a>
                    </h2>
                </div>
            </div>
        @empty
            <div class="nofunds d-">
                <div class="nofunds-text">{{ __('No Funds available now') }}</div>
            </div>
            @endforelse
            {{ $fundboxes->links('site.layouts.includes.pagination') }}
            </div>
        </section>

    </main>
    <!-- End Main -->

    <!-- Modal -->
    @foreach ($fundboxes as $fund)
        <div class="modal fade" id="shareModal--{{ $fund->id }}" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h3 class="modal-title">{{ __('Share Fund') }}</h3>
                        <ul class="socialLinks">
                            <li>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('fundbox.show', $fund->slug) }}"
                                    target="_blank" rel="noopener">
                                    <img src="{{ asset('assets/icons/facebook.png') }}" alt="" />
                                </a>
                            </li>
                            <li>
                                <a href="https://api.whatsapp.com/send/?text={{ route('fundbox.show', $fund->slug) }}&type=custom_url&app_absent=0"
                                    target="_blank"><img src="{{ asset('assets/icons/whatsapp.png') }}"
                                        alt="" /></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/cws/share?url={{ route('fundbox.show', $fund->slug) }}"
                                    target="_blank"><img src="{{ asset('assets/icons/linkedin.png') }}"
                                        alt="" /></a>
                            </li>
                            <li><a href="https://twitter.com/share?url={{ route('fundbox.show', $fund->slug) }}"
                                    target="_blank"><img src="{{ asset('assets/icons/twitter.png') }}"
                                        alt="" /></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/sharer.php?u={{ route('fundbox.show', $fund->slug) }}"><img
                                        src="{{ asset('assets/icons/instagram.png') }}" alt="" /></a>
                            </li>
                        </ul>
                        <button id="copyToClipboard" class="btn mx-auto mb-3 copyToClipboard"
                            data-copy="{{ route('fundbox.show', $fund->slug) }}">
                            {{ __('Copy Link') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Modal --}}
    <div class="modal fade" id="upgrade_pro_account">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center px-5" id="upgrade_pro_account_text"
                    data-swal-msg="{{ __('Upgrade Your Account??') }}"
                    data-swal-image="{{ asset('assets/modal-icons/sure.svg') }}"
                    data-swal-confirm-route="{{ route('upgrade-account-pro') }}"
                    data-swal-confirm-token="{{ csrf_token() }}"
                    data-swal-success-icon="{{ asset('assets/modal-icons/success.svg') }}"
                    data-swal-error-icon="{{ asset('assets/modal-icons/error.svg') }}"
                    data-swal-failed-icon="{{ asset('assets/modal-icons/failed.svg') }}"
                    data-swal-success-msg="{{ __('Account Upgrade Was Successful') }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
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
    <script src="{{ asset('js/upgrade.js') }}"></script>
@endpush
