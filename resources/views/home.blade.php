@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table" id="table-payments">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Goal ref</th>
                                    <th scope="col">Payment Ref</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-payments">


                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function() {
            loadPayments();
        });

        function loadPayments() {

            $('#table-body-payments').html('')

            $.ajax({
                type: "GET",
                url: "/admin/getPayments",
                success: function(response) {
                    $.each(response, function(indexInArray, value) {
                        $('#table-body-payments').append(
                            '<tr>' +
                            '<th scope="row">' + value['no'] + '</th>' +
                            '<th scope="row">' + value['date'] + '</th>' +
                            '<td>' + value['user'] + '</td>' +
                            '<td>' + value['goal'] + '</td>' +
                            '<td>' + value['ref'] + '</td>' +
                            '<td>' + value['status'] + '</td>' +
                            '<td>' + value['action'] + '</td>' +
                            '</tr>'
                        );
                    });
                }
            });
        }

        function approvePayment(id) {
            $.ajax({
                type: "GET",
                url: "/admin/approvePayment",
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response.status);
                    loadPayments();
                }
            });
        }
    </script>
@endsection
