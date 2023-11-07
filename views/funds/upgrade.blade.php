@extends('site.layouts.app')

@section("styles")
<link rel="stylesheet" href="{{asset('css/expert.css')}}" />
@endsection

@section('content')
    <!-- Main -->
    <main>
      <section class="expert-investor">
        <div class="ex__pattern"></div>
        <div class="container">
          <div class="row mb-5">
            <div class="col-lg-7">
              <h1 class="landing__title">مستثمر محترف</h1>
              <h2 class="landing__subtitle">لتتمكن من ترقية حسابك</h2>
            </div>
            <div class="col-lg-5">
              <div class="card">
                <p class="expert_text">
                  فى حال رغبتك في الاستثمار باكثر من 200,000 ريال بالفرصة
                  الواحده يجب ترقية حسابك لمستثمر محترف
                </p>
              </div>
            </div>
          </div>
          <form id="export-form">
            <div class="row">
              <div class="col-lg-6">
                <div class="how">
                  <div class="how__container">
                    <div
                      class="boxes__container flex-center aos-init aos-animate"
                      data-aos="fade-down"
                      data-aos-duration="1000"
                    >
                      <div class="box flex-center">
                        <div class="icon-arr">
                          <div class="box__icon flex-center">
                            <span class="box__num">01</span>
                          </div>
                          <div class="ex_arrow">
                            <img
                              src="/assets/shapes/arrow-ex.svg"
                              alt="Arrow"
                            />
                          </div>
                        </div>
                        <div class="box__content">
                          <p class="box__body">
                            ان يكون قد قام بصفقات في أسواق الأوراق المالية لا
                            يقل مجموع قيمتها عن أربعين مليون ريال سعودي ولا تقل
                            عن عشر صفقات في كل ربع سنة خلال .الاثني عشر شهرا
                            الماضية
                          </p>
                          <div class="file-upload">
                            <div class="file-upload-select">
                              <div class="file-select-name">إرفاق ملف</div>
                              <div class="file-select-button">
                                <i class="fa fa-cloud-arrow-up"></i>
                              </div>
                              <input
                                type="file"
                                name="expert-f1"
                                id="expert-f1"
                                data-index="1"
                                required
                              />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="box flex-center">
                        <div class="icon-arr">
                          <div class="box__icon flex-center">
                            <span class="box__num">02</span>
                          </div>
                          <div class="ex_arrow">
                            <img
                              src="/assets/shapes/arrow-ex.svg"
                              alt="Arrow"
                            />
                          </div>
                        </div>
                        <div class="box__content">
                          <p class="box__body">
                            ان يعمل او سبق له العمل مدة ثلاث سنوات على الأقل في
                            القطاع المالي في .وظيفة مهنية تتلعق بالاستثمار في
                            الأوراق المالية
                          </p>
                          <div class="file-upload">
                            <div class="file-upload-select">
                              <div class="file-select-name">إرفاق ملف</div>
                              <div class="file-select-button">
                                <i class="fa fa-cloud-arrow-up"></i>
                              </div>
                              <input
                                type="file"
                                name="expert-f2"
                                id="expert-f2"
                                data-index="2"
                                required
                              />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="box flex-center">
                        <div class="icon-arr">
                          <div class="box__icon flex-center">
                            <span class="box__num">03</span>
                          </div>
                        </div>
                        <div class="box__content">
                          <p class="box__body">
                            .الا تقل قيمة صافي اصوله عن خمسة ملايين ريال سعودي
                          </p>
                          <div class="file-upload">
                            <div class="file-upload-select">
                              <div class="file-select-name">إرفاق ملف</div>
                              <div class="file-select-button">
                                <i class="fa fa-cloud-arrow-up"></i>
                              </div>
                              <input
                                type="file"
                                name="expert-f3"
                                id="expert-f3"
                                data-index="3"
                                required
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 position-relative">
                <div class="how">
                  <div class="how__container">
                    <div
                      class="boxes__container flex-center aos-init aos-animate"
                      data-aos="fade-down"
                      data-aos-duration="1000"
                    >
                      <div class="box flex-center">
                        <div class="icon-arr">
                          <div class="box__icon flex-center">
                            <span class="box__num">04</span>
                          </div>
                          <div class="ex_arrow">
                            <img
                              src="/assets/shapes/arrow-ex.svg"
                              alt="Arrow"
                            />
                          </div>
                        </div>
                        <div class="box__content">
                          <p class="box__body">
                            ان يكون حاصلا على الشهادة العامة للتعامل في الأوراق
                            المالية المعتمدة من قبل الهيئة , على ان لا يقل دخله
                            السنوي عن ستمائة الف ريال سعودي في السنتين الماضيتين
                          </p>
                          <div class="file-upload">
                            <div class="file-upload-select">
                              <div class="file-select-name">إرفاق ملف</div>
                              <div class="file-select-button">
                                <i class="fa fa-cloud-arrow-up"></i>
                              </div>
                              <input
                                type="file"
                                name="expert-f4"
                                id="expert-f4"
                                data-index="4"
                                required
                              />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="box flex-center">
                        <div class="icon-arr">
                          <div class="box__icon flex-center">
                            <span class="box__num">05</span>
                          </div>
                        </div>
                        <div class="box__content">
                          <p class="box__body">
                            ان يكون حاصلا على شهادة مهنية متخصصة في مجال اعمال
                            الأوراق المالية معتمدة من جهة معترف بها دوليا.
                          </p>
                          <div class="file-upload">
                            <div class="file-upload-select">
                              <div class="file-select-name">إرفاق ملف</div>
                              <div class="file-select-button">
                                <i class="fa fa-cloud-arrow-up"></i>
                              </div>
                              <input
                                type="file"
                                name="expert-f5"
                                id="expert-f5"
                                data-index="5"
                                required
                              />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <button class="sendBtn btn" type="submit" id="sendBtn">
              إرسال <i class="fa fa-arrow-up landing__icon icon"></i>
            </button>
          </form>
        </div>
      </section>
    </main>
    <!-- End Main -->
@endsection

@push('scripts')
<script src="{{asset('js/expert.js')}}"></script>
@endpush

@if ($errors->any())
    @push('scripts')
        <script>
            sweetAlert.fire({
                icon: 'error',
                html: '<div style="color:#dc3545" >{!! implode('<br/>', $errors->all()) !!} </div>',
            })
        </script>
    @endpush
@endif
