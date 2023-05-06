@extends('layout')
@section('content')
	<h2>Parsed Data</h2>
	<table class="table">			
		<thead>
			<tr>
			  <th scope="col">#</th>
			  <th scope="col">Info</th>
			</tr>
		</thead>
		<tbody>
			@foreach($data as $row)
				<tr>
					<th scope="row" class="a1">{{$loop->iteration }}</th>
					<td>
						<p>{{$row[1]}} <a class="text-primary">{{$row[2]}}</a></p>
						<p>{{$row[3]}}</p>
						<p><b>{{$row[4]}}</b></p>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
		