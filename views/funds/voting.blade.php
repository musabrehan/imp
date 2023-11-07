@extends('site.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/voting.css') }}"/>
@endsection

@section('content')
    <!-- Main -->
    <main>
        <section class="voting">
        <div class="container">
            <div class="row">
            <div class="col-lg-6 start__content">
                <form method="post" id="voting_form" action="{{ route('fundbox.polls.update',[$fundbox->slug,$poll->id]) }}" data-user-vote="{{ $selected_option ? '1':'0' }}">
                    @csrf
                    @method('PATCH')
                <h3 class="v_question_title mb-5">
                    {{ $poll->title }}
                </h3>
                @foreach ($poll->options as $option)
                    <div class="form-group v_item">
                        <label for="option_{{ $option->id }}" class="form-label w-100">
                        <input
                            type="radio"
                            name="option"
                            id="option_{{ $option->id }}"
                            class="v_radio"
                            value="{{ $option->id }}"
                            @if($selected_option !== null && $selected_option->id == $option->id)
                                checked
                            @endif
                        />
                        <p class="v_label">{{ $option->title }}</p>
                        <div class="v_hidden_label">
                            <div class="v_title">
                            <span>{{ $option->title }}</span>
                            <span id="option_percentage_{{ $option->id }}">{{ $option->percentage }}%</span>
                            </div>
                            <div class="progress my-2" style="width: 100%">
                            <div
                                id="option_progress_bar_{{ $option->id }}"
                                class="progress-bar progress-bar-striped"
                                role="progressbar"
                                aria-valuenow="100"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                style="width: {{ $option->percentage }}%"
                            ></div>
                            </div>
                            <p id="v_num_{{ $option->id }}" class="v_num">{{ $option->votes_number }}</p>
                        </div>
                        </label>
                    </div>
                @endforeach
                </form>
            </div>
            <div class="col-lg-6 end__content" id="voting_result">
                <div class="v_info mb-3">
                <div class="v_info_card">
                    <div @class([
                        'v_info_item',
                        'col-md-12' => $delay == null || $delay <= 0,
                        'col-md-5' => $delay !== null && $delay > 0,
                    ]) >
                        <p class="side_title">{{ __('Total Votes Number') }}</p>
                        <div id="total_votes" class="side_view">{{ $total_votes }}</div>
                    </div>
                    <div
                        class="d-flex col-2 justify-content-center vr_container"
                        style="height: 180px"
                    >
                    @if($delay !== null && $delay > 0)
                        <div
                            class="vr"
                            style="
                            background-color: rgba(55, 62, 129, 29%);
                            inline-size: 1px;
                            "
                        ></div>
                    @endif
                    </div>
                    @if($delay !== null && $delay > 0)
                        <div class="v_info_item col-md-5 position-relative">
                            <p class="side_title">{{ __('The Remaining Time') }}</p>
                            <div class="rounded-percent">
                                <svg>
                                <defs>
                                    <linearGradient
                                    id="gradient"
                                    x1="0%"
                                    y1="0%"
                                    x2="0%"
                                    y2="100%"
                                    >
                                    <stop offset="0%" stop-color="#00bc9b" />
                                    <stop offset="100%" stop-color="#5eaefd" />
                                    </linearGradient>
                                </defs>
                                <circle cx="50%" cy="50%" r="50"></circle>
                                <circle
                                    cx="50%"
                                    cy="50%"
                                    r="50"
                                    id="percentCircle"
                                ></circle>
                                </svg>
                                <div class="number">
                                <span id="voting_counter" data-delay="{{ $delay }}"></span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                </div>
                <button 
                    id="re-vote" 
                    class="btn" 
                    data-url="{{ route('fundbox.polls.destroy',[$fundbox->slug,$poll->id]) }}" 
                    data-token="{{ csrf_token() }}"
                >
                    <span>{{ __('Edit Your Vote') }}</span>
                    <img src="{{ asset('assets/icons/edit-pen.png') }}" alt="edit-pen" />
                </button>
            </div>
            </div>
        </div>
        </section>
    </main>
    <!-- End Main -->
@endsection

@push('scripts')
    <script src="{{ asset('js/voting.js') }}"></script>
@endpush