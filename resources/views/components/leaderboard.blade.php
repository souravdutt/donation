<div class="mt-5 position-relative">
    @if(!Route::is("home.leaderboard"))
        <a class="btn btn-link btn-sm position-absolute end-0 bottom-0" href="{{ route('home.leaderboard') }}" role="button">See All</a>
    @endif
    <h2 class="text-center">Donor's Leaderboard</h2>
</div>
<div class="card">
    <div class="card-body">
        <table class="table table-striped table-bordered">
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Address</th>
                <th class="text-center">Date</th>
                <th class="text-center">Amount</th>
            </tr>
            @if($donors->count())
                @foreach ($donors as $donor)
                    <tr>
                        <td>{{ $donor->name }}</td>
                        <td class="text-center">{{ $donor->city_name }}, {{ $donor->country_name }}</td>
                        <td class="text-center">{{ $donor->created_at?->format('d M, Y') }}</td>
                        <td class="text-end">({{ env("DONATION_CURRENCY", "INR") }}) {{ number_format($donor->amount, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center"><i class="fa fa-ghost fa-lg"></i> <strong>Oops! </strong> No data to show</td>
                </tr>
            @endif
        </table>
    </div>
</div>
