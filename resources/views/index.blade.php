<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Index</title>
</head>
<body>
<div class="container-float">
    <div class="row show-grid">
    </div>
</div>

{{--scripts--}}
<script src="{{asset($public.'js/jquery-3.4.1.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(function () {
        let usersEndpoint = "{{asset('/')}}/api/tasks";
        $.get( usersEndpoint, function( data ) {
            //console.log(data.users)
            if (data.users) {
                let users = data.users.data;
                let tasks = {};
                let userPoints = {};
                for (let index in data.tasks) {
                    if (!userPoints[data.tasks[index].user_id]) {
                        userPoints[data.tasks[index].user_id] = {};
                        userPoints[data.tasks[index].user_id]['points'] = 0;
                        userPoints[data.tasks[index].user_id]['totalPoints'] = 0;
                    }
                    if (data.tasks[index].children) {
                        for (let key in data.tasks[index].children) {
                            data.tasks[index].points += parseInt(data.tasks[index].children[key].points);
                            userPoints[data.tasks[index].user_id]['totalPoints'] += parseInt(data.tasks[index].children[key].points)
                            if (data.tasks[index].children[key].is_done) {
                                userPoints[data.tasks[index].user_id]['points'] += parseInt(data.tasks[index].children[key].points);
                            }
                        }
                    }
                    if (!tasks[data.tasks[index].user_id]) {
                        tasks[data.tasks[index].user_id] = [];
                    }
                    tasks[data.tasks[index].user_id].push(data.tasks[index]);
                }
                let html = "";
                for (let index in users) {
                    let user = users[index];
                    if (!userPoints[user.id]) {
                        userPoints[user.id] = {};
                        userPoints[user.id].points = 0;
                        userPoints[user.id].totalPoints = 0;
                    }
                    html += '<div class="col"><h5>'+user.first_name+' '+user.last_name+' ('+userPoints[user.id].points+'/'+userPoints[user.id].totalPoints+')</h5>';
                    if (tasks[user.id]) {
                        for (let key1 in tasks[user.id]) {
                            let task = tasks[user.id][key1];
                            html += '<ul><li>'+task.title+' ('+task.points+')</li>';
                            if (task.children) {
                                html += '<li class="list-unstyled"><ul>';
                                for (let key2 in task.children) {
                                    let child = task.children[key2];
                                    html += '<li>'+child.title+' ('+child.points+')</li>';
                                }
                                html += '</ul>';
                            }
                            html += '</ul>';
                        }
                    } else {
                        html += '<p><b>No tasks</b></p>';
                    }

                    html += '</div>';
                }

                $('.row').html(html);
                //console.log(userPoints);
                //console.log(tasks);
            }

        });

    })
</script>
</body>
</html>
