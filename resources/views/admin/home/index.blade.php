@extends('layouts.tabler-admin.master')
@section('content')
    <div class="row row-cards">
        {{-- <div class="col-sm-6 col-lg-6">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-netbeans"
                                    width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                    <path
                                        d="M15.5 9.43a1 1 0 0 1 .5 .874v3.268a1 1 0 0 1 -.515 .874l-3 1.917a1 1 0 0 1 -.97 0l-3 -1.917a1 1 0 0 1 -.515 -.873v-3.269a1 1 0 0 1 .514 -.874l3 -1.786c.311 -.173 .69 -.173 1 0l3 1.787h-.014z" />
                                    <path d="M12 21v-9l-7.5 -4.5" />
                                    <path d="M12 12l7.5 -4.5" />
                                    <path d="M12 3v4.5" />
                                    <path d="M19.5 16l-3.5 -2" />
                                    <path d="M8 14l-3.5 2" />
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                132 Sales
                            </div>
                            <div class="text-secondary">
                                Peminjaman Asset
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-6">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chalkboard"
                                    width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M8 19h-3a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v11a1 1 0 0 1 -1 1" />
                                    <path
                                        d="M11 16m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                132 Aktif
                            </div>
                            <div class="text-secondary">
                                Peminjaman Ruangan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="card">
                <div class="card-header">Jadwal Penggunaan Ruangan</div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                slotMinTime: '7:00:00',
                slotMaxTime: '21:00:00',
                events: @json($events),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
            });
            calendar.render();
        });
    </script>
@endpush
