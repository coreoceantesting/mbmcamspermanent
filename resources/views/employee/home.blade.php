<x-frontend.mobile-layout>
    {{-- <x-slot name="title">{{ auth()->user()->tenant_name }} - Dashboard</x-slot> --}}


    <div class="page-body">
        <div class="container">
            {{-- <div class="card custom-card rounded" style="overflow: initial;">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Employee Code : {{ Auth::user()->emp_code }}</h6>
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle profile-image" height="20" alt="Profile" />
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('employee.logout') }}">Logout</a></li>
                            </ul>
                        </div>

                        <form id="logout-form" action="{{ route('employee.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div> --}}

            <div class="row mt-2">
                <div class="col-6">
                    <div class="card custom-card rounded">
                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's In</h6>
                        <div class="card-footer p-0">
                            <div class="col-12 py-2">
                                <h3 class="counter">{{ $data['todays_in_time'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card custom-card rounded">
                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Today's Out</h6>
                        <div class="card-footer p-0">
                            <div class="col-12 py-2">
                                <h3 class="counter">{{ $data['todays_out_time'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            {{-- Monthly Attendance --}}
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card custom-card rounded">
                        <h6 class="card-header rounded bg-primary py-2 px-3 text-center">Monthly Attendance</h6>
                        <div class="card-footer row p-0">
                            <div class="col-4 py-2">
                                <h6>PRESENT</h6>
                                <h3 class="counter">{{ $data['total_present'] }}</h3>
                            </div>
                            <div class="col-4 py-2">
                                <h6>ABSENT</h6>
                                <h3 class="counter">{{ $data['total_absent'] }}</h3>
                            </div>
                            <div class="col-4 py-2">
                                <h6>LEAVE</h6>
                                <h3 class="counter">{{ $data['total_leave'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            {{-- Filter --}}
            <div class="row mt-1">
                <div class="col-12">
                    <div class="card custom-card rounded">
                        <form action="{{ route('employee.home') }}" method="GET">
                            <div class="card-body row py-0 px-2">
                                <div class="col-5 py-2">
                                    <label class="mb-1" for="month">Month </label>
                                        <select class="col-sm-12 form-control @error('month') is-invalid  @enderror" value="{{ old('month') }}" required name="month">
                                            <option value="">--Select Month--</option>
                                            <option value="1" {{ request()->month == 1 ? 'selected' : '' }}>January</option>
                                            <option value="2" {{ request()->month == 2 ? 'selected' : '' }}>February</option>
                                            <option value="3" {{ request()->month == 3 ? 'selected' : '' }}>March</option>
                                            <option value="4" {{ request()->month == 4 ? 'selected' : '' }}>April</option>
                                            <option value="5" {{ request()->month == 5 ? 'selected' : '' }}>May</option>
                                            <option value="6" {{ request()->month == 6 ? 'selected' : '' }}>June</option>
                                            <option value="7" {{ request()->month == 7 ? 'selected' : '' }}>July</option>
                                            <option value="8" {{ request()->month == 8 ? 'selected' : '' }}>August</option>
                                            <option value="9" {{ request()->month == 9 ? 'selected' : '' }}>September</option>
                                            <option value="10" {{ request()->month == 10 ? 'selected' : '' }}>October</option>
                                            <option value="11" {{ request()->month == 11 ? 'selected' : '' }}>November</option>
                                            <option value="12" {{ request()->month == 12 ? 'selected' : '' }}>December</option>
                                        </select>
                                        @error('month')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>

                                <div class="col-5 py-2">
                                    <label class="mb-1" for="year">Year </label>
                                    <select class="col-sm-12 form-control @error('year') is-invalid  @enderror" value="{{ old('year') }}" required name="year">
                                        <option value="">--Select Year--</option>
                                        <option value="2022" {{ request()->year == 2022 ? 'selected' : '' }}>2022</option>
                                        <option value="2023" {{ request()->year == 2023 ? 'selected' : '' }}>2023</option>
                                        <option value="2024" {{ request()->year == 2024 ? 'selected' : '' }}>2024</option>
                                    </select>
                                    @error('year')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-2 pt-2">
                                    <button type="submit" class="btn btn-primary mt-4 px-2" ><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>





            {{-- TABLE --}}
            <div class="row mt-1">
                <div class="col-12 pb-5">
                    <div class="card rounded">
                        <div class="card-block row rounded">
                            <div class="col-12 ">
                                <div class="table-responsive rounded">
                                    <table class="table table-hover table-striped">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">In Time</th>
                                                <th scope="col">Out Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['attendance'] as $punch)
                                                <tr>
                                                    <td>{{ $punch['date'] }}</td>
                                                    <td>{{ $punch['check_in'] }}</td>
                                                    <td>{{ $punch['check_out'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

</x-frontend.mobile-layout>
