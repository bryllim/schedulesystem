<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ Session::token() }}"> 
        <title>Laravel</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h1 class="text-center  mt-5"><strong>Simple Calendar Scheduling System</strong></h1>
            <h4 class="text-center">Developed with <i>Laravel</i> + <i>JQuery</i> + <i>Bootstrap</i></h4>
            <hr>
            <h4 class="mt-4"><button class="btn btn-light" id="prevMonth"><strong><</strong></button> &nbsp; <strong id="monthPlaceholder"></strong> &nbsp; <button class="btn btn-light" id="nextMonth"><strong>></strong></button><button class="btn btn-primary float-right" id="newSchedule">New Schedule</button></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Sunday</th>
                        <th scope="col">Monday</th>
                        <th scope="col">Tuesday</th>
                        <th scope="col">Wednesday</th>
                        <th scope="col">Thursday</th>
                        <th scope="col">Friday</th>
                        <th scope="col">Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="date_row1"></tr>
                    <tr id="date_row2"></tr>
                    <tr id="date_row3"></tr>
                    <tr id="date_row4"></tr>
                    <tr id="date_row5"></tr>
                </tbody>
            </table>
        </div>

        <!-- New Schedule Modal -->
        <div class="modal fade" id="newScheduleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="description">Enter description</label>
                            <input type="text" class="form-control" id="description" placeholder="Enter description here" required>
                        </div>
                        <div class="form-group">
                            <label for="month">Select month</label>
                            <select class="form-control" id="month" required>
                            <option value="0">January</option>
                            <option value="1">February</option>
                            <option value="2">March</option>
                            <option value="3">April</option>
                            <option value="4">May</option>
                            <option value="5">June</option>
                            <option value="6">July</option>
                            <option value="7">August</option>
                            <option value="8">September</option>
                            <option value="9">October</option>
                            <option value="10">November</option>
                            <option value="11">December</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="day">Enter day</label>
                            <input type="number" class="form-control" id="day" placeholder="Enter from 1 - 31" min="1" max="31" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="submitSchedule" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Schedule Modal -->
        <div class="modal fade" id="viewScheduleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center" id="loader"><i>Fetching data...</i></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
    $( document ).ready(function() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        var tempMonth = new Date();
        var currentMonth = tempMonth.getMonth();
        $("#monthPlaceholder").text(months[currentMonth]);
        getDates();
        
        $("#prevMonth").click(function() {
            currentMonth -= 1;
            if(currentMonth < 0){
                currentMonth = 11;
            }
            $("#monthPlaceholder").text(months[currentMonth]);
            getDates();
        });

        $("#nextMonth").click(function() {
            currentMonth += 1;
            if(currentMonth > 11){
                currentMonth = 0;
            }
            $("#monthPlaceholder").text(months[currentMonth]);
            getDates();
        });

        function getDates(){
            // January
            if(currentMonth == 0){
                $("#date_row1").html(
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td><button class='btn btn-light btn-block'>1</button></td>" +
                    "<td><button class='btn btn-light btn-block'>2</button></td>" +
                    "<td><button class='btn btn-light btn-block'>3</button></td>" +
                    "<td><button class='btn btn-light btn-block'>4</button></td>" 
                );
                $("#date_row2").html(
                    "<td><button class='btn btn-light btn-block'>5</button></td>" +
                    "<td><button class='btn btn-light btn-block'>6</button></td>" +
                    "<td><button class='btn btn-light btn-block'>7</button></td>" +
                    "<td><button class='btn btn-light btn-block'>8</button></td>" +
                    "<td><button class='btn btn-light btn-block'>9</button></td>" +
                    "<td><button class='btn btn-light btn-block'>10</button></td>" +
                    "<td><button class='btn btn-light btn-block'>11</button></td>" 
                );
                $("#date_row3").html(
                    "<td><button class='btn btn-light btn-block'>12</button></td>" +
                    "<td><button class='btn btn-light btn-block'>13</button></td>" +
                    "<td><button class='btn btn-light btn-block'>14</button></td>" +
                    "<td><button class='btn btn-light btn-block'>15</button></td>" +
                    "<td><button class='btn btn-light btn-block'>16</button></td>" +
                    "<td><button class='btn btn-light btn-block'>17</button></td>" +
                    "<td><button class='btn btn-light btn-block'>18</button></td>" 
                );
                $("#date_row4").html(
                    "<td><button class='btn btn-light btn-block'>19</button></td>" +
                    "<td><button class='btn btn-light btn-block'>20</button></td>" +
                    "<td><button class='btn btn-light btn-block'>21</button></td>" +
                    "<td><button class='btn btn-light btn-block'>22</button></td>" +
                    "<td><button class='btn btn-light btn-block'>23</button></td>" +
                    "<td><button class='btn btn-light btn-block'>24</button></td>" +
                    "<td><button class='btn btn-light btn-block'>25</button></td>" 
                );
                $("#date_row5").html(
                    "<td><button class='btn btn-light btn-block'>25</button></td>" +
                    "<td><button class='btn btn-light btn-block'>26</button></td>" +
                    "<td><button class='btn btn-light btn-block'>27</button></td>" +
                    "<td><button class='btn btn-light btn-block'>28</button></td>" +
                    "<td><button class='btn btn-light btn-block'>29</button></td>" +
                    "<td><button class='btn btn-light btn-block'>30</button></td>" +
                    "<td></td>" 
                );
            }

            // February
            if(currentMonth == 1){
                $("#date_row1").html(
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td><button class='btn btn-light btn-block'>1</button></td>"
                );
                $("#date_row2").html(
                    "<td><button class='btn btn-light btn-block'>2</button></td>" +
                    "<td><button class='btn btn-light btn-block'>3</button></td>" +
                    "<td><button class='btn btn-light btn-block'>4</button></td>" +
                    "<td><button class='btn btn-light btn-block'>5</button></td>" +
                    "<td><button class='btn btn-light btn-block'>6</button></td>" +
                    "<td><button class='btn btn-light btn-block'>7</button></td>" +
                    "<td><button class='btn btn-light btn-block'>8</button></td>" 
                );
                $("#date_row3").html(
                    "<td><button class='btn btn-light btn-block'>9</button></td>" +
                    "<td><button class='btn btn-light btn-block'>10</button></td>" +
                    "<td><button class='btn btn-light btn-block'>11</button></td>" +
                    "<td><button class='btn btn-light btn-block'>12</button></td>" +
                    "<td><button class='btn btn-light btn-block'>13</button></td>" +
                    "<td><button class='btn btn-light btn-block'>14</button></td>" +
                    "<td><button class='btn btn-light btn-block'>15</button></td>" 
                );
                $("#date_row4").html(
                    "<td><button class='btn btn-light btn-block'>16</button></td>" +
                    "<td><button class='btn btn-light btn-block'>17</button></td>" +
                    "<td><button class='btn btn-light btn-block'>18</button></td>" +
                    "<td><button class='btn btn-light btn-block'>19</button></td>" +
                    "<td><button class='btn btn-light btn-block'>20</button></td>" +
                    "<td><button class='btn btn-light btn-block'>21</button></td>" +
                    "<td><button class='btn btn-light btn-block'>22</button></td>" 
                );
                $("#date_row5").html(
                    "<td><button class='btn btn-light btn-block'>23</button></td>" +
                    "<td><button class='btn btn-light btn-block'>24</button></td>" +
                    "<td><button class='btn btn-light btn-block'>25</button></td>" +
                    "<td><button class='btn btn-light btn-block'>26</button></td>" +
                    "<td><button class='btn btn-light btn-block'>27</button></td>" +
                    "<td><button class='btn btn-light btn-block'>28</button></td>" +
                    "<td><button class='btn btn-light btn-block'>29</button></td>"
                );
            }

            /**** Incomplete Area */

            if(currentMonth > 1){
                $("#date_row1").html(
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td></td>" +
                    "<td><button class='btn btn-light btn-block'>1</button></td>"
                );
                $("#date_row2").html(
                    "<td><button class='btn btn-light btn-block'>2</button></td>" +
                    "<td><button class='btn btn-light btn-block'>3</button></td>" +
                    "<td><button class='btn btn-light btn-block'>4</button></td>" +
                    "<td><button class='btn btn-light btn-block'>5</button></td>" +
                    "<td><button class='btn btn-light btn-block'>6</button></td>" +
                    "<td><button class='btn btn-light btn-block'>7</button></td>" +
                    "<td><button class='btn btn-light btn-block'>8</button></td>" 
                );
                $("#date_row3").html(
                    "<td><button class='btn btn-light btn-block'>9</button></td>" +
                    "<td><button class='btn btn-light btn-block'>10</button></td>" +
                    "<td><button class='btn btn-light btn-block'>11</button></td>" +
                    "<td><button class='btn btn-light btn-block'>12</button></td>" +
                    "<td><button class='btn btn-light btn-block'>13</button></td>" +
                    "<td><button class='btn btn-light btn-block'>14</button></td>" +
                    "<td><button class='btn btn-light btn-block'>15</button></td>" 
                );
                $("#date_row4").html(
                    "<td><button class='btn btn-light btn-block'>16</button></td>" +
                    "<td><button class='btn btn-light btn-block'>17</button></td>" +
                    "<td><button class='btn btn-light btn-block'>18</button></td>" +
                    "<td><button class='btn btn-light btn-block'>19</button></td>" +
                    "<td><button class='btn btn-light btn-block'>20</button></td>" +
                    "<td><button class='btn btn-light btn-block'>21</button></td>" +
                    "<td><button class='btn btn-light btn-block'>22</button></td>" 
                );
                $("#date_row5").html(
                    "<td><button class='btn btn-light btn-block'>23</button></td>" +
                    "<td><button class='btn btn-light btn-block'>24</button></td>" +
                    "<td><button class='btn btn-light btn-block'>25</button></td>" +
                    "<td><button class='btn btn-light btn-block'>26</button></td>" +
                    "<td><button class='btn btn-light btn-block'>27</button></td>" +
                    "<td><button class='btn btn-light btn-block'>28</button></td>" +
                    "<td><button class='btn btn-light btn-block'>29</button></td>"
                );
            }
            /**** End of Incomplete Area */
        }

        $("#newSchedule").click(function() {
            $('#newScheduleModal').modal('show');
        });

        $(document).on("click", ".btn-block", function() {

            $('#loader').html("<i>Fetching data...</i>");
            var tempday = $(this).text();
            $('#viewScheduleModal').modal('show');

            $.ajax({
                url: '{{route("getschedule")}}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    month: currentMonth,
                    day: tempday,
                },
                dataType: 'JSON',
                success: function (data) { 

                    console.log(data);

                    if(data.msg.length > 0){
                        var displaySchedules = "<ul>";
                        data.msg.forEach(function(i){

                            displaySchedules = displaySchedules + "<li>" + i.description + "</li>";

                        });
                        displaySchedules += "</ul>";
                        $('#loader').html(displaySchedules);
                    }else{
                        $('#loader').html("<strong>No data found.</strong>");
                    }
                    
                },
                error: function(){
                    $('#loader').html("<strong>No data found.</strong>");
                }
            }); 

        });

        $("#submitSchedule").click(function(e) {

            $.ajax({
                url: '{{route("newschedule")}}',
                type: 'POST',
                data: {
                    _token: CSRF_TOKEN,
                    description: $("#description").val(),
                    month: $("#month").val(),
                    day: $("#day").val(),
                },
                dataType: 'JSON',
                success: function (data) { 
                    $('#newScheduleModal').modal('hide');
                    $("#description").val("");
                    $("#day").val("");
                    alert(data.msg); 
                }
            }); 

        });


    });    
    </script>
</html>
