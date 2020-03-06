<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    ​
    <title>Todo-List</title>
    ​
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    ​
    <style>
        .fa-btn {
            margin-right: 6px;
        }
        ​
        table button {
            margin-left: 20px
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            ​
<!-- Branding Image -->
            <a class="navbar-brand" href="">
                Add food
</a>
        </div>
    </div>
</nav>
<div class="container">
    <div class="col-sm-offset-2 col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                new food
            </div>
            ​
            <div class="panel-body">
                <!-- New Task Form -->
                <form action="{{route('food')}}" method="POST" class="form-horizontal">
                    <!-- Task Name -->
                    <div class="form-group">
                        <label for="task-name" class="col-sm-3 control-label">Food</label>
                        ​
                        <div class="col-sm-6">
                            <label for="task-name" class="col-sm-3 control-label">name</label>
                            <input type="text" name="name" id="task-name" class="form-control" value="food4">
                            <label for="task-name" class="col-sm-3 control-label">restaurant_id</label>
                            <input type="text" name="restaurant_id" id="task-name" class="form-control" value="1">
                            <label for="task-name" class="col-sm-3 control-label">remaining</label>
                            <input type="text" name="remaining" id="task-name" class="form-control" value="10">
                            <label for="task-name" class="col-sm-3 control-label">original_price</label>
                            <input type="text" name="original_price" id="task-name" class="form-control" value="10">
                            <label for="task-name" class="col-sm-3 control-label">discounted_price</label>
                            <input type="text" name="discounted_price" id="task-name" class="form-control" value="10">
                        </div>
                    </div>
                    ​
                    <!-- Save Button -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-6">
                            <button type="submit" class="btn btn-default">
                                <i class="fa fa-btn fa-plus"></i>Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Current Tasks -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Tasks
            </div>
            ​
            <div class="panel-body">
                <table class="table table-striped task-table">
                    <thead>
                    <th>food</th>
                    <th>&nbsp;</th>
                    </thead>
                    <tbody>

                            </td>
                            <!-- Task Buttons -->
                            <td class="col-sm-6">

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
