@extends('layouts.default')
@section('content')
<style>
pre{
     padding: 0!important;
     margin: 0!important;
}
script.code {
  display: block !important;
}
</style>
    <div class="container">
        <div class="row">
            <div class="col-sm-5">
                <h4>Buttons</h4>
                <hr/>
                <div class="col-sm-6">
                    <label>btn btn-primary</label>
                    <input type="button" class="btn btn-primary" value="Edit"><br/><br/>
                    <label>btn btn-success</label>
                    <input type="button" class="btn btn-success" value="Submit"><br/><br/>
                    <label>btn btn-default</label>
                    <input type="button" class="btn btn-default" value="Default"><br/><br/>
                    <label>btn btn-info</label>
                    <input type="button" class="btn btn-info" value="Assign"><br/><br/>
                    <label>btn btn-warning</label>
                    <input type="button" class="btn btn-warning" value="Finish"><br/><br/>
                    <label>btn btn-danger</label>
                    <input type="button" class="btn btn-danger" value="Delete"><br/><br/>
                    <label>btn btn-priority</label>
                    <input type="button" class="btn btn-priority" value="Priority"><br/><br/>
                    <label>btn btn-stop</label>
                    <input type="button" class="btn btn-stop" value="Start Timer"><br/><br/>
                    <label>btn btn-black</label>
                    <input type="button" class="btn btn-black" value="Black"><br/><br/>
                </div>
                <div class="col-sm-6">
                    <label>btn-shadow</label>
                    <input type="button" class="btn-shadow btn btn-primary" value="Edit"><br/><br/>
                    <label>btn-transparent</label>
                    <input type="button" class="btn-transparent btn btn-primary" value="Edit"><br/><br/>
                    <label>btn-sm</label>
                    <input type="button" class="btn btn-primary btn-sm" value="Edit"><br/><br/>
                    <label>btn-lg</label>
                    <input type="button" class="btn btn-primary btn-lg" value="Edit"><br/><br/>
                </div>
            </div>
            <div class="col-sm-7">
                <h4>Box</h4>
                <hr/>
                <div class="col-sm-6">
                    <pre>
                       <script class="code" type="text/plain">
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Box Title</h3>
    </div>
    <div class="box-body">
        This is a box body
    </div>
</div>
                       </script>
                    </pre>
                    <label>box-primary</label>
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body">
                            This is a box body
                        </div>
                    </div>
                    <label>box-success</label>
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body">
                            This is a box body
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>box-info</label>
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body">
                            This is a box body
                        </div>
                    </div>
                    <label>box-warning</label>
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body">
                            This is a box body
                        </div>
                    </div>
                    <label>box-danger</label>
                    <div class="box box-danger">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body">
                            This is a box body
                        </div>
                    </div>
                    <label>box-body </label>
                    <div class="box box-default">
                        <div class="box-header">
                            <h3 class="box-title">Box Title</h3>
                        </div>
                        <div class="box-body ">
                            This is a box body
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <h4>Panel</h4>
                <hr/>
                <pre>
                   <script class="code" type="text/plain">
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Panel Title</h3>
    </div>
    <div class="panel-body">
        This is a panel body
    </div>
</div>
                   </script>
                </pre>
                <div class="col-sm-6">
                    <label>panel-primary</label>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>
                    <label>panel-success</label>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>
                    <label>panel-default</label>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Box Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a box body
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>panel-body</label>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Box Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>

                    <label>panel-info</label>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>
                    <label>panel-warning</label>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>
                    <label>panel-danger</label>
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <h3 class="panel-title">Panel Title</h3>
                        </div>
                        <div class="panel-body">
                            This is a panel body
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <h4>Background</h4>
                <hr/>
                <div class="col-sm-6">
                    <label>bg-gray</label>
                    <div class="bg-gray">Gray Background</div>
                    <label>bg-black</label>
                    <div class="bg-black">Black Background</div>
                    <label>bg-red</label>
                    <div class="bg-red">Red Background</div>
                    <label>bg-blue</label>
                    <div class="bg-blue">Blue Background</div>
                    <label>bg-yellow</label>
                    <div class="bg-yellow">Yellow Background</div>
                    <label>bg-aqua</label>
                    <div class="bg-aqua">Aqua Background</div>
                    <label>bg-light-blue</label>
                    <div class="bg-light-blue">Light Blue Background</div>
                    <label>bg-green</label>
                    <div class="bg-green">Green Background</div>
                    <label>bg-navy</label>
                    <div class="bg-navy">Navy Background</div>
                    <label>bg-teal</label>
                    <div class="bg-teal">Teal Background</div>
                    <label>bg-olive</label>
                    <div class="bg-olive">Olive Background</div>
                    <label>bg-lime</label>
                    <div class="bg-lime">Lime Background</div>
                    <label>bg-fuchsia</label>
                    <div class="bg-fuchsia">Fuchsia Background</div>
                    <label>bg-purple</label>
                    <div class="bg-purple">Purple Background</div>
                    <label>bg-maroon</label>
                    <div class="bg-maroon">Maroon Background</div>
                </div>
                <div class="col-sm-6">
                    <label>bg-black-gradient</label>
                    <div class="bg-black-gradient">Black Background</div>
                    <label>bg-red-gradient</label>
                    <div class="bg-red-gradient">Red Background</div>
                    <label>bg-blue-gradient</label>
                    <div class="bg-blue-gradient">Blue Background</div>
                    <label>bg-yellow-gradient</label>
                    <div class="bg-yellow-gradient">Yellow Background</div>
                    <label>bg-aqua-gradient</label>
                    <div class="bg-aqua-gradient">Aqua Background</div>
                    <label>bg-light-blue-gradient</label>
                    <div class="bg-light-blue-gradient">Light Blue Background</div>
                    <label>bg-green-gradient</label>
                    <div class="bg-green-gradient">Green Background</div>
                    <label>bg-teal-gradient</label>
                    <div class="bg-teal-gradient">Teal Background</div>
                    <label>bg-purple-gradient</label>
                    <div class="bg-purple-gradient">Purple Background</div>
                    <label>bg-maroon-gradient</label>
                    <div class="bg-maroon-gradient">Maroon Background</div>
                </div>
            </div>
            <div class="col-sm-3">
                <h4>Text Color</h4>
                <hr/>
                <div class="col-sm-6">
                    <label>text-red</label>
                    <h3 class="text-red">Text Color</h3>
                    <label>text-yellow</label>
                    <h3 class="text-yellow">Text Color</h3>
                    <label>text-aqua</label>
                    <h3 class="text-aqua">Text Color</h3>
                    <label>text-blue</label>
                    <h3 class="text-blue">Text Color</h3>
                    <label>text-black</label>
                    <h3 class="text-black">Text Color</h3>
                    <label>text-light-blue</label>
                    <h3 class="text-light-blue">Text Color</h3>
                    <label>text-green</label>
                    <h3 class="text-green">Text Color</h3>
                    <label>text-navy</label>
                    <h3 class="text-navy">Text Color</h3>
                </div>
                <div class="col-sm-6">
                    <label>text-teal</label>
                    <h3 class="text-teal">Text Color</h3>
                    <label>text-olive</label>
                    <h3 class="text-olive">Text Color</h3>
                    <label>text-lime</label>
                    <h3 class="text-lime">Text Color</h3>
                    <label>text-orange</label>
                    <h3 class="text-orange">Text Color</h3>
                    <label>text-fuchsia</label>
                    <h3 class="text-fuchsia">Text Color</h3>
                    <label>text-purple</label>
                    <h3 class="text-purple">Text Color</h3>
                    <label>text-maroon</label>
                    <h3 class="text-maroon">Text Color</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <h4>Form Validation states</h4>
                <hr/>
                <pre>
                <script class="code" type="text/plain">
<div class="form-group has-warning">
  <label class="control-label" for="inputWarning1">Input with warning</label>
  <input type="text" class="form-control" id="inputWarning1">
</div>
                </script>
                </pre>
                <label>form-group has-success</label>
                <div class="form-group has-success">
                  <label class="control-label" for="inputWarning1">Input with state</label>
                  <input type="text" class="form-control" id="inputWarning1">
                </div>
                <label>form-group has-warning</label>
                <div class="form-group has-warning">
                  <label class="control-label" for="inputWarning1">Input with state</label>
                  <input type="text" class="form-control" id="inputWarning1">
                </div>
                <label>form-group has-error</label>
                <div class="form-group has-error">
                  <label class="control-label" for="inputWarning1">Input with state</label>
                  <input type="text" class="form-control" id="inputWarning1">
                </div>
            </div>
            <div class="col-sm-4">
                <h4>Progress Bar</h4>
                <hr/>
                <pre>
                <script class="code" type="text/plain">
<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
    60%
  </div>
</div>
                </script>
                </pre>
                <label>progress-bar progress-bar-success</label>
                <div class="progress">
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                    <span class="sr-only">40% Complete (success)</span>
                  </div>
                </div>
                <label>progress-bar progress-bar-info</label>
                <div class="progress">
                  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                    <span class="sr-only">40% Complete (success)</span>
                  </div>
                </div>
                <label>progress-bar progress-bar-warning</label>
                <div class="progress">
                  <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                    <span class="sr-only">40% Complete (success)</span>
                  </div>
                </div>
                <label>progress-bar progress-bar-danger</label>
                <div class="progress">
                  <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                    <span class="sr-only">40% Complete (success)</span>
                  </div>
                </div>

                <pre>
                <script class="code" type="text/plain">
<div class="progress-custom">
    <span class="progress-val">50%</span>
    <span class="progress-bar-custom"><span class="progress-in" style="width: 50%"></span></span>
</div>
                </script>
                </pre>
                <label>Custom Progress Bar</label>
                <div class="progress-custom">
                    <span class="progress-val">50%</span>
                    <span class="progress-bar-custom"><span class="progress-in" style="width: 50%"></span></span>
                </div>
            </div>
        </div>
    </div>
@stop